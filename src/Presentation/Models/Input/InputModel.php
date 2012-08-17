<?php
namespace Presentation\Models\Input;
use Fuel\Validation\Base;
abstract class InputModel
{
    protected $data;
    protected $validator;
    protected $messages;

    public function __construct($data, $msgs = []) {
        $this->data = $data;
        $this->validator = new Base();
        $this->messages = [];
        $this->setDefaultMessages();
        $this->messages = array_merge($this->messages, $msgs);
    }

    public function isValid()
    {
        $this->initValidation();

        $success = $this->validator->execute($this->data);

        $this->applyMessages();

        return $success;
    }

    abstract protected function initValidation();

    public function applyMessages()
    {
        foreach($this->messages as $key => $msg) {
            $parts = explode('.', $key);
            $error = $this->validator->getError($parts[0]);
            if($error && $value = $error->getValue())
                $value->getValidation()->setMessage($parts[1], $msg);
        }
    }

    public function __get($key)
    {
        return @$this->data[$key];
    }

    public function __call($key, $args)
    {
        return @$this->data[$key];
    }

    public function getMessageFor($key)
    {
        return $this->validator->getErrorMessage($key);
    }

    protected function setDefaultMessages()
    {

    }
}