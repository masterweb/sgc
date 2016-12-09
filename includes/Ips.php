<?php
class Ips {

    private $_ipReal;


    private function iP() {
        if (@$_SERVER) {
            if ( @$_SERVER['HTTP_X_FORWARDED_FOR'] ) {
                $this->_ipReal= $_SERVER["HTTP_X_FORWARDED_FOR"];
            }elseif ( @$_SERVER["HTTP_CLIENT_IP"] ) {
                $this->_ipReal= @$_SERVER["HTTP_CLIENT_IP"];
            }else {
                $this->_ipReal= @$_SERVER["REMOTE_ADDR"];
            }
        }else {
            if( getenv( "HTTP_X_FORWARDED_FOR" ) ) {
                $this->_ipReal= getenv( "HTTP_X_FORWARDED_FOR" );
            }elseif( getenv( "HTTP_CLIENT_IP" ) ) {
                $this->_ipReal= getenv( "HTTP_CLIENT_IP" );
            } else {
                $this->_ipReal= getenv( "REMOTE_ADDR" );
            }
        }
    }

    public function  __getIp() {
        $this->iP();
        return "'$this->_ipReal'";
    }
}
?>
