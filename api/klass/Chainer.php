<?php
interface Handler
{
    public function setNext($handler);
    public function getType();
    public function handle($request);
    //function determine($str);
}

abstract class Chainer implements Handler
{
    private $nextHandler;
    private $type;
    
     public function __construct($type)
    {
        $this->type = $type;
    }
    
    
    protected function determine($str){
        
        if(is_int($str)){
            return false;
        }
        if ($str[0] === $this->type)
        {
            if (strlen($str) > 1)
            {
                return $this->type;
                //return strtoupper($this->type);
            }
            return $this->type . ''. $str;
            //return strtoupper($this->type . ''. $str);
        }
        return false;
    }


   
    public function setNext($handler)
    {
        $this->nextHandler = $handler;
        return $handler;
    }
    public function handle($request)
    {
        $res = $this->determine($request);
        if (!$res){
            if ($this->nextHandler){
                return $this->nextHandler->handle($request);
            }
            return '';
        }
        return $res;
    }
    public function getType(){
        return $this->type;
    }
}