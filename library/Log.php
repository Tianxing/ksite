<?php
class Log {
    
    protected $currentInstance = null;
    protected $logLevel = 0;
    protected $file = '';

    protected static $logId = 0;
    protected static $instance = array();
    

    const LOG_LEVEL_FATAL   = 0x01;
    const LOG_LEVEL_WARNING = 0x02;
    const LOG_LEVEL_NOTICE  = 0x04;
    const LOG_LEVEL_DEBUG   = 0x08;

    public static $logLevels = array(
        self::LOG_LEVEL_FATAL   => 'FATAL',
        self::LOG_LEVEL_WARNING => 'WARNING',
        self::LOG_LEVEL_NOTICE  => 'NOTICE',
        self::LOG_LEVEL_DEBUG   => 'DEBUG',
    );

    private function __construct($logConf) {

        $this->logLevel = $logConf['logLevel'];
        $this->file = $logConf['file'];
    }

    public static function getInstance($name = '') {

        if(!$name) {
            $name = APP_NAME;
        }

        if(!self::$logId) {
            self::getLogId();
        }

        if(!empty(self::$instance[$name])) {
            
            return self::$instance[$name];
        }

        $logConf = Conf::getConf("/log/{$name}");

        if(!isset($logConf['logLevel']) || !$logConf['logLevel']) {

            $logConf['logLevel'] =  Conf::getConf("/log/logLevel");
        }

        if(!isset($logConf['logLevel']) || !$logConf['logLevel']) {

            $logConf['logLevel'] = self::LOG_LEVEL_NOTICE;
        }

        if(!isset($logConf['file']) || !$logConf['file']) {

            $logConf['file'] = LOG_PATH . '/' . $name . '.log';
        }

        self::$instance[$name] = new Log($logConf);

        return self::$instance[$name];
    }

    public function debug($str, $errno = 0)
    {
        return $this->writeLog(self::LOG_LEVEL_DEBUG, $str, $errno);
    }

    public function notice($str, $errno = 0)
    {
        return $this->writeLog(self::LOG_LEVEL_NOTICE, $str, $errno);
    }

    public function warning($str, $errno = 0)
    {
        return $this->writeLog(self::LOG_LEVEL_WARNING, $str, $errno);
    }

    public function fatal($str, $errno = 0)
    {
        return $this->writeLog(self::LOG_LEVEL_FATAL, $str, $errno);
    }

    private function writeLog($level, $str, $errno = 0) {

        if( $level > $this->logLevel || !isset(self::$logLevels[$level]) )
        {
            return;
        }

        $file = $this->file;
        if( ($level & self::LOG_LEVEL_WARNING) || ($level & self::LOG_LEVEL_FATAL) )
        {
            $file .= '.wf';
        }

        $file .= '.'.date('YmdH');

        
        $trace = debug_backtrace();
        $logStr = self::$logLevels[$level] . ":" . strftime('%y-%m-%d %H:%M:%S') . " [" . basename($trace[1]['file']) . ":" . $trace[1]['line'] . "] ";

        if($errno) {
            $logStr .= "errno[{$errno}] ";
        }

        $logStr .= "logId[" . LOG_ID . "] uri[{$_SERVER['REQUEST_URI']}] cookie[{$_SERVER['HTTP_COOKIE']}] refer[";
        if(isset($_SERVER['HTTP_REFERER'])) {
            
            $logStr .= $_SERVER['HTTP_REFERER'];
        }
        $logStr .= "] error[{$str}]\n";

        return file_put_contents($file, $logStr, FILE_APPEND);
    }

    public static function getLogId() {

        if(self::$logId) {
            
            return self::$logId;
        }

        $arr = gettimeofday();
        self::$logId = ((($arr['sec']*100000 + $arr['usec']/10) & 0x7FFFFFFF) | 0x80000000) . rand(10000000, 99999999);
        define('LOG_ID', self::$logId);

        return self::$logId;
    }
}
