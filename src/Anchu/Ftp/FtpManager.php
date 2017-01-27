<?php namespace Anchu\Ftp;

class FtpManager {

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The active connection instances.
     *
     * @var array
     */
    protected $connections = array();

    /**
     * Create a new FTP instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct(\Illuminate\Foundation\Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['ftp.default'];
    }

    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getConfig($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        // To get the ftp connection configuration, we will just pull each of the
        // connection configurations and get the configurations for the given name.
        // If the configuration doesn't exist, we'll throw an exception and bail.
        $connections = $this->app['config']['ftp.connections'];

        if (is_null($config = array_get($connections, $name)))
        {
            throw new \InvalidArgumentException("Ftp [$name] not configured.");
        }

        return $config;
    }

    /**
     * Make the FTP connection instance.
     *
     * @param  string  $name
     * @return \Anchu\Ftp\Ftp
     */
    protected function makeConnection($name)
    {
        $config = $this->getConfig($name);
        return new Ftp($config);
    }

    /**
     * Get a FTP connection instance.
     *
     * @param  string  $name
     */
    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        // If we haven't created this connection, we'll create it based on the config
        // provided in the application.
        if ( ! isset($this->connections[$name]))
        {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Disconnect from the given ftp.
     *
     * @param  string  $name
     * @return void
     */
    public function disconnect($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if ($this->connections[$name])
        {
            $this->connections[$name]->disconnect();
            unset($this->connections[$name]);
        }
    }

    /**
     * Reconnect to the given ftp.
     *
     * @param  string  $name
     * @return \Anchu\Ftp\Ftp
     */
    public function reconnect($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        $this->disconnect($name);

        return $this->connection($name);
    }

    /**
     * Return all of the created connections.
     *
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }

}
