<?php


class Externals_SimplePie_File extends WP_SimplePie_File {

    var $url;
    var $useragent = 'Externals';
    var $success = true;
    var $headers = array();
    var $body;
    var $status_code;
    var $redirects = 0;
    var $error;
    var $method = SIMPLEPIE_FILE_SOURCE_REMOTE;
    
    protected $aArgs = array(
        'timeout'     => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'user-agent'  => 'Externals',
        'blocking'    => true,
        'headers'     => array(
            'Accept' => 'application/atom+xml, application/rss+xml, application/rdf+xml;q=0.9, application/xml;q=0.8, text/xml;q=0.8, text/html;q=0.7, unknown/unknown;q=0.1, application/unknown;q=0.1, */*;q=0.1'
        ),
        'cookies'     => array(),
        'body'        => null,
        'compress'    => false,
        'decompress'  => true,
        'sslverify'   => true,
        'stream'      => false,
        'filename'    => null
    ); 
    
    public function __construct( $sURL, $iTimeout=10, $iRedirects=5, $aHeaders=null, $sUserAgent=null, $bForceFsockOpen=false ) {

        $this->timeout   = $iTimeout;
        $this->redirects = $iRedirects;
        $this->headers   = $aHeaders;
        $this->useragent = $sUserAgent;        
        $this->url       = $sURL;

        // If the scheme is not http or https.
        if ( ! preg_match( '/^http(s)?:\/\//i', $sURL ) ) {
            $this->error = '';
            $this->success = false;            
            return;
        }
            
        // Arguments
        $_aHTTPArguments     = apply_filters(
            Externals_Registry::HOOK_SLUG . '_filter_simiplepie_http_arguments',
            $this->_getDefaultHTTPArguments()
        );        
        $_aHTTPArguments     = array(
            'user-agent' => $sUserAgent,
            'raw' => true,  // returns the raw response data
        ) + $_aHTTPArguments;

        // Request
        $_oHTTP = new Externals_HTTPClient( 
            $sURL,  // urls
            isset( $_aHTTPArguments[ 'cache_duration' ] ) 
                ? $_aHTTPArguments[ 'cache_duration' ]
                : 86400,
            $_aHTTPArguments
        );
        $_aoResponse = $_oHTTP->get();

        if ( is_wp_error( $_aoResponse ) ) {
            
            $this->error   = 'WP HTTP Error: ' . $_aoResponse->get_error_message();
            $this->success = false;
            return;
            
        } 
            
        $this->headers     = wp_remote_retrieve_headers( $_aoResponse );
        $this->body        = wp_remote_retrieve_body( $_aoResponse );
        $this->status_code = wp_remote_retrieve_response_code( $_aoResponse );    
        
    }
    
        /**
         * @return      array
         */
        private function _getDefaultHTTPArguments() {
            
            $aArgs     = array(
                'timeout'       => $this->timeout,
                'redirection'   => $this->redirects, true,
                'sslverify'     => false, // this is missing in WP_SimplePie_File
            );        
            if ( ! empty( $this->headers ) ) {
                $aArgs[ 'headers' ] = $this->headers;
            }
            if ( SIMPLEPIE_USERAGENT != $this->useragent ) {
                $aArgs[ 'user-agent' ] = $this->useragent;
            }
            
            return $aArgs;
            
        }    
    
}