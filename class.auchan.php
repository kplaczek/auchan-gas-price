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
        if ($this->shops === array()) {
            $page = file_get_contents(self::BASE_URL . self::SHOPS_URL);
            preg_match('#<div class="shops-columns">.*?</div>#ism', $page, $found);
            preg_match_all('#href="(.*?)">(.*?)<\/a>#ism', $found[0], $found);
            $this->shops = $found;
            
            if ($found !== array()) {
                $this->shops = $found;
            }
        }
        return $this->shops;
    }
    
    public function getCities(){
        $shops = $this->getShops();
        return $shops[2];
    }

    public function getPrices() {
        foreach ($this->shops[1] as $key => $shop) {
            $content = file_get_contents(self::BASE_URL . $shop);
            $status = preg_match_all('#<p class="boxType3-info">(E 95|E 98|ON|LPG): <span>(\d,\d\d)#ism', $content, $found);
            if ((bool)$status) {
                $this->prices[$this->shops[2][$key]] = array_combine($found[1], $found[2]);
            }
        }
        return $this->prices;
    }

}

?>
