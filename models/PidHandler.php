<?php

class PidHandler
{
    protected $_fullProcessFileName;

    protected $_basePath;

    protected $_procDir;

    /**
     * @param Phalcon\Config $config
     */
    function __construct(Phalcon\Config $config)
    {
        $this->_procDir = $config->procDir;
        if (substr($this->_procDir, -1) !== '/') {
            $this->_procDir .= '/';
        }

        $this->_basePath = $config->path;
        if (substr($this->_basePath, -1) !== '/') {
            $this->_basePath .= '/';
        }

        $this->_fullProcessFileName = $this->_basePath . $config->name . '.pid';
    }

    function __destruct()
    {
        if (file_exists($this->_fullProcessFileName)) {
            unlink($this->_fullProcessFileName);
        }
    }

    function setFile()
    {
        if (file_exists($this->_fullProcessFileName)) {
            throw new Exception('process "' . $config->name . '" is already running or not stopped properly');
        }

        if (!file_exists($this->_basePath)) {
            throw new Exception(PHP_EOL . PHP_EOL . 'Create directory with proper permissions: sudo mkdir -pm 777 ' . $this->_basePath . PHP_EOL . PHP_EOL);
        }

        file_put_contents($this->_fullProcessFileName, getmypid());
    }

    /**
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->_fullProcessFileName);
    }

    /**
     * @return boolean
     */
    public function removeIfExists()
    {
        if (file_exists($this->_fullProcessFileName)) {
            $running = file_exists($this->_procDir . file_get_contents($this->_fullProcessFileName));
            unlink($this->_fullProcessFileName);
            return $running;
        }

        return false;
    }

}
