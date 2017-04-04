$(function () {
        $("#room").change(
            function () {
                var firstPersonSelector = $("#together1-group");
                var secondPersonSelector = $("#together2-group");

                firstPersonSelector.show();
                secondPersonSelector.show();

                if (this.selectedIndex === 2) {
                    firstPersonSelector.hide();
                    secondPersonSelector.hide();
                }

                if (this.selectedIndex === 1) {
                    secondPersonSelector.hide();
                }

            });
    }
);
