<?php namespace Dryval;

use Illuminate\Support\MessageBag;

class ValidationException extends \Exception{

    protected $validationErrors;

    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        $this->validationErrors = $message instanceof MessageBag ? $message : new MessageBag((array) $message);

        parent::__construct(is_string($message) ? $message : 'Validation error', $code, $previous);
    }

    public function getMessages()
    {
        return $this->validationErrors;
    }

} 