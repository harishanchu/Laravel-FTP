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
        if(!isset($config['port']))
            $config['port'] = 21;
        if(!isset($config['timeout']))
            $config['timeout'] = 90;

        $connectionId = ftp_connect($config['host'],$config['port'],$config['timeout']);
        if ($connectionId) {
            $loginResponse = ftp_login($connectionId, $config['username'], $config['password']);
            ftp_pasv($connectionId, $config['passive']);
        }

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
    public function getDirListing($directory = '.', $parameters = null)
    {
        if($parameters)
            $directory = $parameters . '  ' . $directory;

        try {
            $contentsArray = ftp_nlist($this->connectionId, $directory);

            return $contentsArray;
        } catch(\Exception $e) {
            return false;
        }

    }

    /**
     * Create new directory
     *
     * @param $directory
     * @return bool
     */
    public function makeDir($directory)
    {
        try {
            if (ftp_mkdir($this->connectionId, $directory))
                return true;
            else
                return false;
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Change directory
     *
     * @param $directory
     * @return bool
     */
    public function changeDir($directory)
    {
        try {
            if(ftp_chdir($this->connectionId, $directory))
                return true;
            else
                return false;
        } catch(\Exception $e) {
            return false;
        }
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
        try {
            if(ftp_put($this->connectionId, $fileTo, $fileFrom, $this->findTransferModeForFile($fileFrom)))
                return true;
            else
                return false;
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Download a file
     * 
     * @param $fileFrom
     * @param $fileTo
     * @return bool
     */
    public function downloadFile($fileFrom, $fileTo)
    {
        $fileInfos = explode('.', $fileFrom);
        $extension = end($fileInfos);

        $mode = $this->findTransferModeForExtension($extension);

        try {
            if (ftp_get($this->connectionId, $fileTo, $fileFrom, $mode, 0))
                return true;
            else
                return false;
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Download a file to output buffer and return
     *
     * @param $fileFrom
     * @return bool|string
     */
    public function readFile($fileFrom)
    {
        try {
            $fileTo = "php://output";
            ob_start();
            $result = $this->downloadFile($fileFrom, $fileTo);
            $data = ob_get_contents();
            ob_end_clean();
        } catch(\Exception $e) {
            return false;
        }

        if($result)
            return $data;
        else
            return $result;
    }

    /**
     * Changes to the parent directory.
     *
     * @return bool
     */
    public function moveUp()
    {
        try {
            return ftp_cdup($this->connectionId);
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Set permissions on a file.
     *
     * @param $mode
     * @param $filename
     * @return int
     */
    public function permission($mode, $filename)
    {
        try {
            return ftp_chmod($this->connectionId, $mode, $filename);
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Deletes the file specified by path from the FTP server.
     *
     * @param $path
     * @return bool
     */
    public function delete($path)
    {
        try {
            return ftp_delete($this->connectionId, $path);
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Returns the current directory name
     *
     * @return string
     */
    public function currentDir()
    {
        try {
            return ftp_pwd($this->connectionId);
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Renames a file or a directory on the FTP server
     *
     * @param $oldName
     * @param $newName
     * @return bool
     */
    public function rename($oldName, $newName)
    {
        try {
        return ftp_rename($this->connectionId, $oldName, $newName);
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Removes a directory.
     *
     * @param $directory
     * @return bool
     */
    public function removeDir($directory)
    {
        try {
        return ftp_rmdir($this->connectionId, $directory);
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Returns the size of the given file
     *
     * @param $remoteFile
     * @return int
     */
    public function size($remoteFile)
    {
        try {
            return ftp_size($this->connectionId, $remoteFile);
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Returns the last modified time of the given file
     *
     * @param $remoteFile
     * @return int
     */
    public function time($remoteFile)
    {
        try {
            return ftp_mdtm($this->connectionId, $remoteFile);
        } catch(\Exception $e) {
            return false;
        }
    }

}
