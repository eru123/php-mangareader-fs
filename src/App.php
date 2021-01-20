<?php

namespace MangaReaderFS;

use \Linker\Request\Resolve;
use \Linker\Frecbase\Store;

class App {
    private $config;
    public $store;
    private $id;
    public function __construct(array $config){
        $config = [
            "dir" => $config["dir"] ?? "", // Default Directory of HakuNeko
            "app_name" => $config["app_name"] ?? "Manga Reader",
            "app_icon" => $config["app_icon"] ?? "",
            "app_css" => $config["app_css"] ?? [],
            "app_db_dir" => $config["app_db_dir"] ?? "db",
            "app_db_doc" => $config["app_db_doc"] ?? "eru123.mangareader-fs.app.db",
        ];
        $this->store = (new Store($config["app_db_dir"] ?? "db",$config["app_db_doc"] ?? "app"))->getData();
        $this->id = md5(self::get_ip());
        $this->config = $config;

        if(!is_array($this->store->data)) $this->store->data = [];
        if(!isset($this->store->data[$this->id])) {
            $this->store->data[$this->id] = [];
        }
    }
    public function save(){
        $this->store->optimize();
    }
    public function isManga(string $manga){
        
    }
    public function isChapter(string $manga,string $chapter){
        
    }
    public function addManga(string $manga){
        if(!isset($this->store->data[$this->id][$manga])) $this->store->data[$this->id][$manga] = FALSE;
    }
    public function addChapter(string $manga,string $chapter){
        $this->store->data[$this->id][$manga] = $chapter;
    }
    public function run(string $method = "web") // (web|api)
    {   
        if($method == "api"){
            Resolve::json([]);
        } elseif ($method == "web") {

        } else {
            Resolve::json(["error"=>"Invalid runtime method"]);
        }
    }
    public static function get_ip()
    {
        // check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && self::validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        // check for IPs passing through proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // check if multiple ips exist in var
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
                $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($iplist as $ip) {
                    if (self::validate_ip($ip)) {
                        return $ip;
                    }
                }
            } else {
                if (self::validate_ip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
            }
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED']) && self::validate_ip($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        }

        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && self::validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && self::validate_ip($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }

        if (!empty($_SERVER['HTTP_FORWARDED']) && self::validate_ip($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        }

        // return unreliable ip since all else failed
        return $_SERVER['REMOTE_ADDR'];
    }
    public static function validate_ip($ip)
    {
        if (strtolower($ip) === 'unknown') return false;
        $ip = ip2long($ip);
        if ($ip !== false && $ip !== -1) {
            $ip = sprintf('%u', $ip);
            if ($ip >= 0 && $ip <= 50331647) return false; 
            if ($ip >= 167772160 && $ip <= 184549375) return false;
            if ($ip >= 2130706432 && $ip <= 2147483647) return false;
            if ($ip >= 2851995648 && $ip <= 2852061183) return false;
            if ($ip >= 2886729728 && $ip <= 2887778303) return false;
            if ($ip >= 3221225984 && $ip <= 3221226239) return false;
            if ($ip >= 3232235520 && $ip <= 3232301055) return false;
            if ($ip >= 4294967040) return false;
        }
        return true;
    }
}