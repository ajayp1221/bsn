<?php

namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\Utility\Inflector;
use Cake\Collection\Collection;

class UploadBehavior extends Behavior {

    private $_s3Config = [
        'version' => 'latest',
        'region' => 'ap-southeast-1',
        'debug'   => false,
        'credentials' => [
            'key' => 'AKIAJRXXNLWTSUENKULA',
            'secret' => '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'
        ]
    ];
    private $_bucket = "bsn-zkp";
    private $_keywords = [
        "lehenga",
        "lehengas",
        "salwar suit",
        "saree",
        "kurti",
        "business suit",
        "maxi",
        "skirt",
        "sherwani",
        "crop top",
        "bridal lehenga",
        "kidswear",
        "jeans",
        "gowns",
        "shirt",
        "footwear",
        "shoe",
        "ladies slippers",
        "floaters"
    ];
    private $field = 'img';
    private $imageQuality = 90;
    private $config = [
//        'Markets' => [
//            'sizes' => [
//                '220x0' => [220, 0],
//                '40x0' => [40, 0],
//            ],
//            'dirPattern' => "{WWW_ROOT}uploads{DS}markets{DS}", // http://v3.zakoopi.com/uploads/markets/ + name + -size.jpg
//            'slugColumns' => "market_name"
//        ]
    ];

    public function initialize(array $config) {
        parent::initialize($config);
        $this->imageQuality = $config['imageQuality'];
        $this->field = $config['uploadField'];
        $this->config = $config['config'];
    }
    
    private function makeIcon($source = null,$destination = null,$defaultBackground = "grey"){
        try{
            $im = new \Imagick();
            $im->readimagefile(fopen('http://bsn-zkp.s3-ap-southeast-1.amazonaws.com/'.$source, 'r'));
        } catch (\ImagickException $ex) {
            return FALSE;
        }
        $im->setimageformat('jpeg');
        $h = $im->getimageheight();
        $w = $im->getimagewidth();
        $bestfit = false;
        
        if($h > $w){
            $newW = $w;
            $newH = $w;
            $im->scaleimage($newW, 0, $bestfit);
        }else{
            $newW = $h;
            $newH = $h;
            $im->scaleimage(0, $newH, $bestfit);
        }
        $im2 = new \Imagick();
        $im2->newimage($newW, $newH, $defaultBackground);
        $h = $im->getimageheight();
        $w = $im->getimagewidth();
        $im2->compositeimage($im, \Imagick::COMPOSITE_ATOP,($newW - $w)/2,($newH - $h)/2);
        $im2->setimageformat('jpeg');
        $im2->scaleimage(100, 100);
        $s3 = new \Aws\S3\S3Client($this->_s3Config);
        $s3result = $s3->putObject(array(
            'Bucket' => $this->_bucket,
            'ACL'    => 'public-read',
            'ContentType' => 'image/jpeg',
            'Key' => $destination,
            'Body' => $im2->getimageblob(),
            'CacheControl' => 'max-age=172800',
            'Expires' => '2100-01-01T00:00:00Z',
//                'SourceFile' => $file['tmp_name'],
        ));
        $res = [
            $im2->getimagewidth(),
            $im2->getimageheight()
        ];
        $im->destroy();
        $im2->destroy();
        return $res;
    }
    private function makeRatio($source = null,$destination = null, $defaultBackground = "white", $maxWidth = 440){
        try{
            $im = new \Imagick();
            $im->readimagefile(fopen('http://bsn-zkp.s3-ap-southeast-1.amazonaws.com/'.$source, 'r'));
        } catch (\ImagickException $ex) {
            return false;
        }
        $im->setimageformat('jpeg');
//        echo $source."  <br>";
//        echo $im->getimagewidth()."  <br>";
        if($im->getimagewidth() > 1440){
            $im->scaleimage(1440, 0);
        }
//        $im = new \Imagick($source);
        $h = $im->getimageheight();
        $w = $im->getimagewidth();
        $ratio = $h/$w;
//        echo $ratio;
        $bestfit = true;
        if($ratio > 2.5){
            $im3 = new \Imagick();
            $im3->newimage($maxWidth, $maxWidth * 2, $defaultBackground);
            $im->scaleimage(440, 0);
//            $im3->scaleimage($maxWidth, $maxWidth * 2 , false);
            $im3->compositeimage($im, \Imagick::COMPOSITE_ATOP,(440 - $im->getimagewidth() )/2,(880 - $im->getimageheight() )/2);
            $im3->setimageformat('jpeg');
            $im = clone $im3; 
            $h = $im->getimageheight();
            $w = $im->getimagewidth();
            $ratio = $h/$w;
        }
        if($ratio > 1.5 && $ratio <= 2.5){
            $newW = $maxWidth;
            $newH = $maxWidth * $ratio;
            if($newH > 660){
                $newH = 660;
            }
            $bestfit = true;
        }
        if($ratio <= 1.5){
            $newW = $maxWidth;
            $newH = $maxWidth * $ratio; 
        }
        $im2 = new \Imagick();
        $im2->newimage($newW, $newH, $defaultBackground);
        $im->scaleimage($newW, $newH, $bestfit);
        $h = $im->getimageheight();
        $w = $im->getimagewidth();
        $im2->compositeimage($im, \Imagick::COMPOSITE_ATOP,($newW - $w)/2,($newH - $h)/2);
        $im2->setimageformat('jpeg');
//        $im2->scaleimage(320,0); // Change Size for Final Output
        
        $s3 = new \Aws\S3\S3Client($this->_s3Config);
        $s3result = $s3->putObject(array(
            'Bucket' => $this->_bucket,
            'ACL'    => 'public-read',
            'ContentType' => 'image/jpeg',
            'Key' => $destination,
            'Body' => $im2->getimageblob(),
            'CacheControl' => 'max-age=172800',
            'Expires' => '2100-01-01T00:00:00Z',
//                'SourceFile' => $file['tmp_name'],
        ));
        
        $res = [
            $im2->getimagewidth(),
            $im2->getimageheight()
        ];
        $im->destroy();
        $im2->destroy();
        if(isset($im3)){
            $im3->destroy();
        }
        return $res;
    }

    /**
     * Send like "{WWW_ROOT}uploads{DS}markets{DS}" and it will return like "D:\project\webroot\uploads\markets"
     * @param string $path
     * @return string
     */
    private function _buildPath($path = null,\Cake\ORM\Entity $entity) {
        $replacements = [
//            '{WWW_ROOT}' => ROOT . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR,
            '{WWW_ROOT}' => '',
            '{DS}' => DIRECTORY_SEPARATOR,
            '//' => DIRECTORY_SEPARATOR,
            '/' => DIRECTORY_SEPARATOR,
            '\\' => DIRECTORY_SEPARATOR
        ];
        $dirClmns = @$this->config[$entity->source()]['dirColumns'];
        if(isset($dirClmns)){
            foreach($dirClmns as $v){
                $replacements["{".$v."}"] = $entity->get($v);
            }
        }
        $builtPath = str_replace(array_keys($replacements), array_values($replacements), $path);
//        if(!is_dir(substr($builtPath, 0, strrpos( $builtPath, '/')))){
//            @mkdir(substr($builtPath, 0, strrpos( $builtPath, '/')), 0775, true);
//        }
        return $builtPath;
    }

    private function _buildSlug(\Cake\ORM\Entity $entity) {
        if ($entity->source() == "Trends") {
            $tmps = \Cake\ORM\TableRegistry::get('Trends');
            $a = $entity->toArray();
            $entity = $tmps->find()->contain(['Cities'])->where(['Trends.id' => $entity->get('id')])->first();
            $tbl  = \Cake\ORM\TableRegistry::get('Cities');
            $c = $tbl->get($a['city_id']);
            $value = Inflector::slug($a['trend_name'] . " " . $c->city_name);
            return strtolower($value);
        }
        if ($entity->source() == "Cards") {

            $keywords = $this->_keywords;

            $keywords = new \Cake\Collection\Collection($keywords);
            $desc = $entity->description;
            $title = "Gone wild to select a perfect shirt for myself..";
            $slug = $keywords->filter(function ($value, $key, $iterator) use (&$desc) {
                        return strpos($desc, $value) !== false;
                    })->toArray();
            if (count($slug) == 0) {
                $table = \Cake\ORM\TableRegistry::get('Lookbooks');
                $lbook = $table->get($entity->lookbook_id);
                $title = $lbook->title;
                $slug = $keywords->filter(function ($value, $key, $iterator) use (&$title) {
                            return strpos($title, $value) !== false;
                        })->toArray();
                if (count($slug) == 0) {
                    $slug = [
                        $lbook->title
                    ];
                }
            }

            $slug = implode(',', $slug);
            $slug = \Cake\Utility\Inflector::slug($slug);

            $stores = explode(",", $entity['tags']);
            $strTable = \Cake\ORM\TableRegistry::get('Stores');
            $strs = $strTable->find('all')->contain(['Cities'])->where([
                "Stores.store_name IN" => $stores
            ]);
            $slug_mkt = [];
            $slug_city = [];
            foreach ($strs as $s) {
                $slug_mkt[] = $s->market;
                $slug_city[] = $s->city->city_name;
            }
            $slug_mkt = implode(",", array_unique($slug_mkt));
            $slug_city = implode(",", array_unique($slug_city));
            
            $value = Inflector::slug($slug . "-" . $slug_mkt . "-" . $slug_city);
            return strtolower(substr($value,0,30) . "-" . $entity->get('timestamp'));
        }
        if ($entity->source() == "Metatables"){
            $slugColumns = $this->config[$entity->source()]['slugColumns'];
            $str = "";
            foreach ($slugColumns as $t) {
                $str .= $entity->get($t) . " ";
            }
            $value = Inflector::slug(substr($str, 0, 6).rand(999,98596).time());

            return strtolower($value);
        }
        $slugColumns = $this->config[$entity->source()]['slugColumns'];
        $str = "";
        foreach ($slugColumns as $t) {
            $str .= $entity->get($t) . " ";
        }
        $value = Inflector::slug(substr($str, 0, 100));
        
        return strtolower($value);
    }

    private function scaleImageJpeg($imgPath, \Cake\ORM\Entity $entity) {
//        if (!is_file($imgPath)) {
//            return false;
//        }
        $s3 = new \Aws\S3\S3Client($this->_s3Config);
        $img = new \Imagick();
        $img->readimagefile(fopen('http://bsn-zkp.s3-ap-southeast-1.amazonaws.com/'.$imgPath, 'r'));
        foreach ($this->config[$entity->source()]['sizes'] as $k => $v) {
            $newIm = clone $img;
            $newIm->scaleimage($v[0], $v[1]);
            $newIm->setcompressionquality($this->imageQuality);
            $newIm->stripimage();
            $newIm->setformat('jpg');
            $newIm->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG); 
            $result = $s3->putObject(array(
                'Bucket' => $this->_bucket,
                'ACL'    => 'public-read',
                'ContentType' => 'image/jpeg',
                'Key' => $impth = rtrim($imgPath, ".jpg") . "-" . $k . ".jpg",
                'Body' => $newIm->getimageblob(),
                'CacheControl' => 'max-age=172800',
                'Expires' => '2100-01-01T00:00:00Z',
//                'SourceFile' => $file['tmp_name'],
            ));
        }
        if($entity->source() == "Users"){
            $img->scaleimage(50, 0);
            $img->setcompressionquality($this->imageQuality);
            $img->stripimage();
            $img->setformat('jpg');
            $img->setcompression(\Imagick::COMPRESSION_LOSSLESSJPEG); 
            $destination = str_replace("/profile_pics/", "/profile_pics/50x50/", $imgPath);
//            $destination = "uploads/profile_pics/50x50/" . $imgPath;       
            
            $result = $s3->putObject(array(
                'Bucket' => $this->_bucket,
                'ACL'    => 'public-read',
                'ContentType' => 'image/jpeg',
                'Key' => $destination,
                'Body' => $img->getimageblob(),
                'CacheControl' => 'max-age=172800',
                'Expires' => '2100-01-01T00:00:00Z',
            ));       
        }
        return true;
        //$img->
    }

    private function mbeforeSave($field,\Cake\ORM\Entity $entity){
        $s3 = new \Aws\S3\S3Client($this->_s3Config);
        $tstamp = "-" . time();
        if ($entity->source() == "Cards") {
            $tstamp = "";
        }
        if(is_string($entity->get('file_' . $field))){
            if(strstr($entity->get('file_' . $field), "http://") != false || strstr($entity->get('file_' . $field), "https://") != false){
                if (isset($entity->id)) {
                    foreach ($this->config[$entity->source()]['sizes'] as $k => $v) {
                        
                        if($entity->source() == "Users"){
                            $s3->deleteObject([
                                'Bucket' => $this->_bucket,
                                'Key' => $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field),$entity)
                            ]);
                            $filename = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . rtrim($entity->get($field),".jpg") . "-" . $k . ".jpg",$entity);
                            $s3->deleteObject([
                                'Bucket' => $this->_bucket,
                                'Key' => $filename
                            ]);
                        }else{
                            $s3->deleteObject([
                                'Bucket' => $this->_bucket,
                                'Key' => $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field) . ".jpg",$entity)
                            ]);
                            $filename = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field) . "-" . $k . ".jpg",$entity);
                            $s3->deleteObject([
                                'Bucket' => $this->_bucket,
                                'Key' => $filename
                            ]);
                        }
                        
                    }
                }
                $OrignalName = substr($this->_buildSlug($entity),0,40) . $tstamp;
                $destination = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $OrignalName . ".jpg",$entity);
                $result = $s3->putObject(array(
                    'Bucket' => $this->_bucket,
                    'ACL'    => 'public-read',
                    'ContentType' => 'image/jpeg',
                    'Key' => $destination,
                    'Body' => file_get_contents($entity->get('file_' . $field)),
                    'CacheControl' => 'max-age=172800',
                    'Expires' => '2100-01-01T00:00:00Z',
    //                'SourceFile' => $file['tmp_name'],
                ));
                if ($entity->source() == "Users") {
                    $OrignalName = $OrignalName .'.jpg';
                }
                
                $entity->set($field, $OrignalName);
                $this->scaleImageJpeg($destination, $entity);
            }
        }elseif ($entity->get('file_' . $field) != null && $entity->get('file_' . $field)['error'] == 0) {
            if (isset($entity->id)) {
                foreach ($this->config[$entity->source()]['sizes'] as $k => $v) {
                    if($entity->source() == "Users"){
                        $dr = $s3->deleteObject([
                            'Bucket' => $this->_bucket,
                            'Key' => $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field),$entity)
                        ]);
                        $filename = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . rtrim($entity->get($field),".jpg") . "-" . $k . ".jpg",$entity);
                        $dr = $s3->deleteObject([
                            'Bucket' => $this->_bucket,
                            'Key' => $filename
                        ]);
                    }else{
                        $dr = $s3->deleteObject([
                            'Bucket' => $this->_bucket,
                            'Key' => $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field) . ".jpg",$entity)
                        ]);
                        $filename = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field) . "-" . $k . ".jpg",$entity);
                        $dr = $s3->deleteObject([
                            'Bucket' => $this->_bucket,
                            'Key' => $filename
                        ]);
                    }
                    
                }
            }
            $OrignalName = $this->_buildSlug($entity) . $tstamp;
            
            $destination = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $OrignalName . ".jpg",$entity);
            
            $s3result = $s3->putObject(array(
                'Bucket' => $this->_bucket,
                'ACL'    => 'public-read',
                'ContentType' => 'image/jpeg',
                //            'Key'    => $file['name'],
                //            'Body'   => fopen($file['tmp_name'], 'r+')
                'SourceFile' => $entity->get('file_' . $field)['tmp_name'],
                'Key' => $destination,
                'CacheControl' => 'max-age=172800',
                'Expires' => '2100-01-01T00:00:00Z',
            ));
            if ($s3result) {
                if ($entity->source() == "Users") {
                    $OrignalName = $OrignalName .'.jpg';
                }
                $entity->set($field, $OrignalName );
                if($entity->source() == "Cards"){
                    $iconDes = $this->_buildPath($this->config[$entity->source()]['dirPattern']."tiny". DS . $OrignalName . ".jpg",$entity);
                    $mediumDes = $this->_buildPath($this->config[$entity->source()]['dirPattern']."medium". DS . $OrignalName . ".jpg",$entity);
//                    $largeDes = $this->_buildPath($this->config[$entity->source()]['dirPattern']."large". DS . $OrignalName . ".jpg",$entity);
                    $this->makeIcon($destination, $iconDes);
                    $m_dim = $this->makeRatio($destination, $mediumDes);
//                    $l_dim = $this->makeRatio($destination, $largeDes, $defaultBackground = "white", 720);
                    $entity->set("medium_img_w", $m_dim[0]);
                    $entity->set("medium_img_h", $m_dim[1]);
                }else{
                    $this->scaleImageJpeg($destination, $entity);
                }
                
            }
        } else {
            $entity->unsetProperty($field);
        }
    }


    public function beforeSave(Event $event, \Cake\ORM\Entity $entity) {
        ini_set('max_execution_time', -1);
        set_time_limit(90000000);
        if(is_array($this->field)){
            foreach($this->field as $f){
                $this->mbeforeSave($f, $entity);
            }
        }else{
            $this->mbeforeSave($this->field, $entity);
        }
    }
    public function mbeforeDelete($field, \Cake\ORM\Entity $entity) {
        $s3 = new \Aws\S3\S3Client($this->_s3Config);
        foreach ($this->config[$entity->source()]['sizes'] as $k => $v) {
            if($entity->source() == "Users"){
                $dr = $s3->deleteObject([
                    'Bucket' => $this->_bucket,
                    'Key' => $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field),$entity)
                ]);
                $filename = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . rtrim($entity->get($field),".jpg") . "-" . $k . ".jpg",$entity);
                $dr = $s3->deleteObject([
                    'Bucket' => $this->_bucket,
                    'Key' => $filename
                ]);
            }else{
                $dr = $s3->deleteObject([
                    'Bucket' => $this->_bucket,
                    'Key' => $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field) . ".jpg",$entity)
                ]);
                $filename = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . $entity->get($field) . "-" . $k . ".jpg",$entity);
                $dr = $s3->deleteObject([
                    'Bucket' => $this->_bucket,
                    'Key' => $filename
                ]);
            }
            
            if($entity->source() == "Cards"){
                $filename = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . "tiny". DS . $entity->get($field) . ".jpg",$entity);
                $s3->deleteObject([
                    'Bucket' => $this->_bucket,
                    'Key' => $filename
                ]);
                $filename = $this->_buildPath($this->config[$entity->source()]['dirPattern'] . "medium". DS . $entity->get($field) . ".jpg",$entity);
                $s3->deleteObject([
                    'Bucket' => $this->_bucket,
                    'Key' => $filename
                ]);
            }
        }
    }
    public function beforeDelete(Event $event, \Cake\ORM\Entity $entity) {
        ini_set('max_execution_time', -1);
        set_time_limit(90000000);
        if(is_array($this->field)){
            foreach($this->field as $f){
                $this->mbeforeDelete($f, $entity);
            }
        }else{
            $this->mbeforeDelete($this->field, $entity);
        }
        
    }

}
