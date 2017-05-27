<?php

namespace App\Controller;

class ServerStatsController extends AppController {

    private function getSystemMemInfo() {
        $data = explode("\n", file_get_contents("/proc/meminfo"));
        $meminfo = array();
        foreach ($data as $line) {
            list($key, $val) = explode(":", $line);
            $meminfo[$key] = trim($val);
        }
        return $meminfo;
    }

    
    
    public function getStorageLog() {
        $data = shell_exec("df -h");
        $this->set('data', $data);
        $this->set('_serialize', ['data']);
    }

    public function getMemLog() {
//        $data = shell_exec("free");
        $data = $this->getSystemMemInfo();
        $this->set('data', $data);
        $this->set('_serialize', ['data']);
    }
    
//    public function getStorageLog() {
//        $data = shell_exec("df -h");
//        $this->set('data', $data);
//        $this->set('_serialize', ['data']);
//    }

}
