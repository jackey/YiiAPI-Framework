<?php
/**
 * File CAPIApplication.php
 * @author jackeychen <jackey@fumer.cn>
 * @link http://yii-api.fumer.cn
 * @copyright 2016-2016 Fumer Software LLC
 * @license ??
 * @package yii_api
 * @since 1.0
 */

class CAPIApplication extends CApplication {

    const STATUS_API_DOT_ERROR = 500;
    const STATUS_MISSED_VERSION = 501;
    const STATUS_API_MISSED = 502;
    const STATUS_BASE_PATH_NOT_EXIST = 503;

    public $defaultAPIPoint = "";
    public $displayHandler = 'json'; // 数据显示模式: json / xml

    public function processRequest() {
        global $version;
        $request = $this->getRequest();
        $uri = $request->getUrl();
        if ($uri == '/' || empty($uri)) $apiPoint = $this->getDefaultAPIPoint();
        else $apiPoint = substr($uri, 1);

        $apiPoint = preg_replace("/\?.*/i", '', $apiPoint);

        if (strpos($apiPoint, '.') === false)
            throw new CAPIException(self::STATUS_API_DOT_ERROR ,'Sorry, the uri of api only supports dot model ( like user.info ). please read the document to get more details.');

        $v = $request->getParam('v');
        if (empty($v)) throw new CAPIException(self::STATUS_MISSED_VERSION, 'The version value required');
        $version = $v;

        $apiInstance = $this->loadAPIInstance($apiPoint);
        if (!$apiInstance) throw new CAPIException(self::STATUS_API_MISSED, 'API has not supported yet');

        $apiInstance->handleRequest();
        $data = $apiInstance->getReturnData();
        $this->displayData($data);
    }

    public function loadAPIInstance($apiPoint) {
        global $version;

        $basePath = $this->basePath;
        if (!is_dir($basePath))
            throw new CAPIException(self::STATUS_BASE_PATH_NOT_EXIST, 'Project base path is not existed');
        $restPath = $basePath.DIRECTORY_SEPARATOR.'rest';
        $this->initAllSupportedAPIAndGroupWithVersion($restPath);

        $apiMapping = $this->initAllSupportedAPIAndGroupWithVersion();
        if (!array_key_exists($version, $apiMapping)) {
            $version = array_pop(array_keys($apiMapping));
        }
        if (empty($apiMapping[$version][$apiPoint])) throw new CAPIException(self::STATUS_API_MISSED, 'API has not supported yet');
        $file = $apiMapping[$version][$apiPoint];
        if (is_file($file)) {
            // Read class name from source file
            // Reference <http://stackoverflow.com/questions/7153000/get-class-name-from-file>
            $f = fopen($file, 'r');
            $class = $buffer = '';
            $i = 0;
            while (!$class) {
                $buffer = fread($f, 521);
                if (strpos($buffer, '{') === false) continue;
                $tokens = token_get_all($buffer);

                if (count($tokens) > 0) {
                    for (; $i < count($tokens); $i++) {
                        if ($tokens[$i][0] == T_CLASS) {
                            for ($j=$i+1;$j<count($tokens);$j++) {
                                if ($tokens[$j] === '{')
                                    $class = $tokens[$i+2][1];
                            }
                        }
                    }
                }
            }
            if (empty($class)) throw new CAPIException(self::STATUS_API_MISSED, 'API has not supported yet');

            require_once $file;
            return new $class;
        }
        else
            throw new CAPIException(self::STATUS_API_MISSED, 'API has not supported yet');
    }

    public function getDefaultAPIPoint() {
        if ($this->defaultAPIPoint) return $this->defaultAPIPoint;
        return 'home.index';
    }

    public function initAllSupportedAPIAndGroupWithVersion($apiBasePath = false, $subDir = '') {
        static $apiMapping;
        if (!$apiBasePath) {
            // TODO:: sort with version number
            return $apiMapping;
        }

        if (!empty($subDir)) $scanDir = $apiBasePath.DIRECTORY_SEPARATOR.$subDir;
        else $scanDir = $apiBasePath;

        $dir = opendir($scanDir);

        while (false != ($filename = readdir($dir))) {
            if ($filename == '.' || $filename == '..') continue;

            if (is_dir($scanDir.DIRECTORY_SEPARATOR.$filename)) {
                $this->initAllSupportedAPIAndGroupWithVersion($apiBasePath, ( $subDir ? $subDir.DIRECTORY_SEPARATOR: $subDir).$filename);
            }
            else if (is_file($scanDir.DIRECTORY_SEPARATOR.$filename)) {
                $tmp = "$subDir".DIRECTORY_SEPARATOR.$filename;
                preg_match("/^v([\d\.]+)/", $tmp, $matches);
                if (!empty($matches)) {
                    $crtv = $matches[1];
                    $prefixv = $matches[0];
                    $tmp = str_replace(array($prefixv, '.php'), '', $tmp);
                    $apiName = str_replace(DIRECTORY_SEPARATOR, '.', substr($tmp, 1));

                    $apiMapping[$crtv][$apiName] = $scanDir.DIRECTORY_SEPARATOR.$filename;
                }
            }
        }
    }

    public function displayData($data) {
        if ($this->displayHandler == 'json') {
            header('Content-Type: application/json; charset=utf-8');
            echo CJSON::encode($data);
            die();
        }
        else if ($this->displayHandler == 'xml') {
            //TODO::
        }
    }
}
 