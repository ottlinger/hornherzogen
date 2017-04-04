<?php

namespace hornherzogen\chart;


class ChartHelper
{
    public function getByGender()
    {
        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"Topping\",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices\",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [
                {\"c\":[{\"v\":\"Mushrooms\",\"f\":null},{\"v\":3,\"f\":null}]},
                {\"c\":[{\"v\":\"Onions\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Olives\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Zucchini\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Pepperoni\",\"f\":null},{\"v\":2,\"f\":null}]}
              ]
        }";
    }

    public function getByWeek($week)
    {
        return "{
          \"cols\": [
                {\"id\":\"\",\"label\":\"Topping " . $week . "\",\"pattern\":\"\",\"type\":\"string\"},
                {\"id\":\"\",\"label\":\"Slices " . $week . "\",\"pattern\":\"\",\"type\":\"number\"}
              ],
          \"rows\": [
                {\"c\":[{\"v\":\"Mushrooms\",\"f\":null},{\"v\":3,\"f\":null}]},
                {\"c\":[{\"v\":\"Onions\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Olives\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Zucchini\",\"f\":null},{\"v\":1,\"f\":null}]},
                {\"c\":[{\"v\":\"Pepperoni\",\"f\":null},{\"v\":2,\"f\":null}]}
              ]
        }";
    }


}