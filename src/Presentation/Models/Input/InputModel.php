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
        $errorMessages = $this->validator->getErrorMessage();
        foreach($this->messages as $key => $msg) {
            $parts = explode('.', $key);
            $field = $parts[0]; $errorKey = $parts[1];
            if(isset($errorMessages[$field]) && $errorMessages[$field] == $errorKey)
                $this->messages[$field] = $msg;
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
        $message = @$this->messages[$key];
        return $message;
    }

    protected function setDefaultMessages()
    {

    }
}