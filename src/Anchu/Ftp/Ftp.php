<?php namespace Anchu\Ftp;

class Ftp {

    /**
     * The active FTP connection resource id.
     */
    protected $connectionId;

    /**
     * Create a new ftp connection instance.
     *
     * @param  config
     * @return void
     */
    public function __construct($config)
    {
        $this->connectionId = $this->connect($config);
    }


    /**
     * Establish ftp connection
     *
     * @param $config
     * @return resource
     * @throws \Exception
     */
    public function connect($config)
    {
        $connectionId = ftp_connect($config['host']);
        $loginResponse = ftp_login($connectionId, $config['username'], $config['password']);
        ftp_pasv($connectionId, $config['passive']);
        ftp_set_option($connectionId, FTP_TIMEOUT_SEC, 300);

        if ((!$connectionId) || (!$loginResponse))
            throw new \Exception('FTP connection has failed!');

        return $connectionId;
    }

    /**
     * Disconnect active connection.
     *
     * @param  config
     * @return void
     */
    public function disconnect()
    {
        ftp_close($this->connectionId);
    }

    /**
     * Get directory listing
     *
     * @param string $directory
     * @param string $parameters
     * @return array
     */
    public function getDirListing($directory = '.', $parameters = '-la')
    {
        $contentsArray = ftp_nlist($this->connectionId, $parameters . '  ' . $directory);

        return $contentsArray;
    }

    /**
     * Create new directory
     *
     * @param $directory
     * @return bool
     */
    public function makeDir($directory)
    {
        if (ftp_mkdir($this->connectionId, $directory))
            return true;
        else
            return false;
    }

    /**
     * Change directory
     *
     * @param $directory
     * @return bool
     */
    public function changeDir($directory)
    {
        if(ftp_chdir($this->connectionId, $directory))
            return true;
        else
            return false;
    }

    /**
     * Determine transfer mode for a local file
     *
     * @param $file
     * @return int
     */
    public function findTransferModeForFile($file)
    {
        $path_parts = pathinfo($file);

        if (!isset($path_parts['extension']))
            return FTP_BINARY;
        else
            return $this->findTransferModeForExtension($path_parts['extension']);


    }

    /**
     * Determine ftp transfer mode for a file extension
     * 
     * @param $extension
     * @return int
     */
    public function findTransferModeForExtension($extension)
    {
        $extensionArray = array(
            'am', 'asp', 'bat', 'c', 'cfm', 'cgi', 'conf',
            'cpp', 'css', 'dhtml', 'diz', 'h', 'hpp', 'htm',
            'html', 'in', 'inc', 'js', 'm4', 'mak', 'nfs',
            'nsi', 'pas', 'patch', 'php', 'php3', 'php4', 'php5',
            'phtml', 'pl', 'po', 'py', 'qmail', 'sh', 'shtml',
            'sql', 'tcl', 'tpl', 'txt', 'vbs', 'xml', 'xrc', 'csv'
        );

        if(in_array(strtolower($extension),$extensionArray))
            return FTP_ASCII;
        else
            return FTP_BINARY;
    }

    /**
     * Upload a file
     *
     * @param $fileFrom
     * @param $fileTo
     * @return bool
     */
    public function uploadFile($fileFrom, $fileTo)
    {
        $upload = @ftp_put($this->connectionId, $fileTo, $fileFrom, $this->findTransferModeForFile($fileFrom));

        if ($upload)
            return true;
        else
            return false;
    }

    /**
     * Download a file
     * 
     * @param $fileFrom
     * @param $fileTo
     * @return bool
     */
    public function downloadFile ($fileFrom, $fileTo)
    {
        $extension = end(explode('.', $fileFrom));

        $mode = $this->findTransferModeForExtension($extension);
        
        if (ftp_get($this->connectionId, $fileTo, $fileFrom, $mode, 0))
            return true;    
        else
            return false;
    }

}