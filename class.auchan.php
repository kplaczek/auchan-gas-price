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
            $this->shops = $found[1];
            if ($found !== array()) {
                $this->shops = array_combine($found[1], $found[2]);
            }
        }
        return $this->shops;
    }

    public function getCities() {
        $shops = $this->getShops();
        return array_values($shops);
    }

    public function getUrls() {
        $shops = $this->getShops();
        return array_keys($shops);
    }

    public function getPrices() {
        foreach ($this->shops as $url => $shopName) {
            $content = file_get_contents(self::BASE_URL . $url);
            $status = preg_match_all('#<p class="boxType3-info">(E 95|E 98|ON|LPG): <span>(\d,\d\d)#ism', $content, $found);
            if ((bool) $status) {
                $this->prices[$shopName] = array_combine($found[1], $found[2]);
            }
        }
        return $this->prices;
    }

    public function getPrice($shopUrl) {
        $content = file_get_contents(self::BASE_URL . $shopUrl);
        $status = preg_match_all('#<p class="boxType3-info">(E 95|E 98|ON|LPG): <span>(\d,\d\d)#ism', $content, $found);
        if ((bool) $status) {
            return array_combine($found[1], $found[2]);
        }
        return false;
    }

}
