$(function () {
        $("#room").change(
            function () {
                var hh_firstPersonSelector = $("#together1-group");
                var hh_secondPersonSelector = $("#together2-group");

                hh_firstPersonSelector.show();
                hh_secondPersonSelector.show();

                if (this.selectedIndex === 2) {
                    hh_firstPersonSelector.hide();
                    hh_secondPersonSelector.hide();
                }

                if (this.selectedIndex === 1) {
                    hh_secondPersonSelector.hide();
                }

            });
    }
);
