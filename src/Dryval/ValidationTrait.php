<?php namespace Dryval;

use Illuminate\Support\Facades\Validator;

trait ValidationTrait {

    /**
     * Register Model Event on boot.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            return $model->validate();
        });
    }

    /**
     * Do Laravel validation
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate()
    {
        $rules = $this->processRules($this->getRules());
        $validator = Validator::make($this->getAttributes(), $rules, $this->getMessages());

        if ($validator->fails()) {
            throw new ValidationException($validator->messages());
        }

        return true;
    }

    /**
     * Enable using placeholders instead of id integers
     *
     * @param array $rules
     * @return array
     */
    protected function processRules(array $rules)
    {
        $id = $this->getKey();
        array_walk($rules, function(&$item) use ($id)
        {
            // Replace placeholders
            $item = stripos($item, ':id:') !== false ? str_ireplace(':id:', $id, $item) : $item;
        });

        return $rules;
    }

    /**
     * @param $rules
     */
    public function setRules($rules)
    {
        static::$rules = $rules;
    }

    /**
     * @return mixed
     */
    public function getRules()
    {
        return static::$rules;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages)
    {
        static::$messages = $messages;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return isset(static::$messages) ? static::$messages : array();
    }
} 