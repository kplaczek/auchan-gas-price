<?php

class auchan {

    const BASE_URL = "http://www.auchan.pl";
    const SHOPS_URL = "/nasze-sklepy/mapa-sklepow-auchan";

    private $shops = array();
    private $prices = array();

    public function __costruct() {
        $this->getShops();
    }

    public function getShops() {
        $page = file_get_contents(self::BASE_URL . self::SHOPS_URL);
        preg_match('#<div class="shops-columns">.*?</div>#ism', $page, $found);
        preg_match_all('#href="(.*?)">(.*?)<\/a>#ism', $found[0], $found);
        $this->shops = $found;
        return $found[1];
    }

    
    
    public function getPrices() {
        foreach ($this->shops[1] as $key => $shop) {
            $content = file_get_contents(self::BASE_URL . $shop);
            preg_match_all('#<p class="boxType3-info">(E 95|E 98|ON|LPG): <span>(.*?)<\/span><\/p>#ism', $content, $found);
            if (!empty($found[0])) {
                $this->prices[$this->shops[2][$key]] = array_combine($found[1], $found[2]);
            }
        }
        return $this->prices;
    }

}
?>
