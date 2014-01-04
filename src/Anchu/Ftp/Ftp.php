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
     */
    public function connect($config)
    {
        $connectionId = ftp_connect($config['host']);
        $loginResponse = ftp_login($connectionId, $config['username'], $config['password']);
        ftp_pasv($connectionId, $config['passive']);
        ftp_set_option($connectionId, FTP_TIMEOUT_SEC, 300);

        if ((!$connectionId) || (!$loginResponse))
            die('FTP connection has failed!');

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

}