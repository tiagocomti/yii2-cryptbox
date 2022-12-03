<?php

namespace tiagocomti\cryptbox\models;

class Data
{
    public $text;
    public $key;
    public $nonce;
    public $create_at;

    public function __construct($cipher, $secretKeyHex, $nonce = null, $time= null){
        if($cipher){$this->setText($cipher);}
        if($secretKeyHex){$this->setKey($secretKeyHex);}
        if($nonce){$this->setNonce($nonce);}
        if($time){$this->setCreateAt($time);}
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $cipher
     */
    public function setText($cipher): void
    {
        $this->text = $cipher;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @param mixed $nonce
     */
    public function setNonce($nonce): void
    {
        $this->nonce = $nonce;
    }

    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @param mixed $create_at
     */
    public function setCreateAt($create_at): void
    {
        $this->create_at = $create_at;
    }


}