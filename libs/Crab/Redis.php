<?php
/**
 * php redis proxy
 * this script basic php redis.so module
 *
 * @author allen.mh@alibaba-inc.com
 */
class Crab_Redis
{
    /**
     * config parameter
     */
    protected static $_arrOption = array(
        'timeout' => 1, 
    );
    /**
     * singleton
     */
    protected static $instance = null;
    /**
     * redis.so object
     */
    public $redis = null;
    /**
     * set redis option
     */
    public static function setOption( $arrOption )
    {
        self::$_arrOption = array_merge( self::$_arrOption, $arrOption ); 
    }
    public static function getInstance()
    {
        if( self::$instance == null ){
            self::$instance = new Crab_Redis();
        } 
        return self::$instance;
    }

    private function __construct()
    {
        $this->redis = new Redis();  

        $host = self::$_arrOption['master']['host'];
        $port = self::$_arrOption['master']['port'];
        $timeout = self::$_arrOption['timeout'];
        if( empty( $port ) ){
            $port = 6379; 
        }
        if( self::$_arrOption['conn_type'] == 'connect' ){
            $this->connect( $host,$port,$timeout ); 
        } else {
            $this->pconnect( $host,$port,$timeout ); 
        }
    }
    /**
     *
     * @param key 
     * @param value 
     * @param ttl seconds
     *
     * @return 
     */
    public function set( $key,$value,$ttl=60 )
    {
        if( is_array ( $value ) )
            $value = json_encode( $value );
        return $this->redis->setEx( $key, $ttl, $value );
    }
    /**
     * get
     */
    public function get( $key )
    {
        return $this->redis->get( $key ); 
    }
    /**
     * get by json_decode
     */
    public function getbydecode( $key )
    {
        return json_decode( $this->redis->get( $key ), true ); 
    }
    public function incr( $key )
    {
        return $this->redis->incr( $key );
    }
    public function decr( $key )
    {
        return $this->redis->decr( $key ); 
    }
    /**
     * append string at list head 
     */
    public function lpush( $key, $value )
    {
        return $this->redis->lpush( $key, $value ); 
    }
    public function rpush( $key, $value )
    {
        return $this->redis->rpush( $key, $value ); 
    }
    public function lpop( $key )
    {
        return $this->redis->lpop( $key ); 
    }
    public function lsize( $key )
    {
        return $this->redis->lsize( $key ); 
    }
    public function lget( $key, $index )
    {
        return $this->redis->lget( $key, $index ); 
    }
    public function lset( $key, $index, $value )
    {
        return $this->redis->lset( $key, $index, $value ); 
    }
    public function spop( $key )
    {
        return $this->redis->spop( $key ); 
    }
    public function ping()
    {
        return $this->redis->ping(); 
    }
    /**
     * transparent redis.so function
     */
    public function __call( $function,$arrArgs )
    {
        return $this->redis->$function();
    }
    /**
     * connect
     */
    private function connect( $host,$port,$timeout )
    {
        $this->redis->connect( $host, $port,$timeout );     
    }
    /**
     * persistent connection
     */
    private function pconnect( $host,$port,$timeout )
    {
        $this->redis->pconnect( $host,$port,$timeout ); 
    }
}
