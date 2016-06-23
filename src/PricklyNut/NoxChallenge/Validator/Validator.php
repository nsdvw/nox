<?php
namespace PricklyNut\NoxChallenge\Validator;

class Validator
{
    protected $language;

    public function __construct($language)
    {
        $this->language = $language;
    }

    public function errorMessages()
    {
        return array(
            'ru' => array(
                'notEmpty' => "Поле %s не может быть пустым",
                'maxLength' => "Максимальная длина поля %s - %s символов",
                'alphabetic' => "Поле %s может содержать только буквы",
                'isEmail' => "Поле %s должно содержать корректный электронный адрес",
                'minLength' => "Минимальная длина поля %s - %s символов",
            ),
            'en' => array(
                'notEmpty' => "%s cannot be empty",
                'maxLength' => "%s must be maximum %s symbols",
                'alphabetic' => "%s must contain only alphabetic characters",
                'isEmail' => "%s must be valid email address",
                'minLength' => "%s must be minimum %s symbols",
            ),
        );
    }

    public function validate($object)
    {
        $errorMessages = $this->errorMessages();
        $rulesPack = $object->getRules();

        foreach ($rulesPack as $field => $rules) {
            if ($rules == null) continue;
            foreach ($rules as $validator => $param) {
                $validatorName = 'validate' . ucfirst($validator);
                $fieldGetter = 'get' . ucfirst($field);
                if (!$this->$validatorName($object->$fieldGetter(), $param)) {
                    $message = $errorMessages[$this->language][$validator];
                    $label = $object->getLabels()[$this->language][$field];
                    $message = sprintf($message, $label, $param);
                    $object->addError($field, $message);
                }
            }
        }

        if (!$object->getErrors()) {
            return true;
        }
        return false;
    }

    public function validateNotEmpty($value)
    {
        return $value !== '';
    }

    public function validateMaxLength($value, $param)
    {
        return mb_strlen($value) <= $param;
    }

    public function validateMinLength($value, $param)
    {
        return mb_strlen($value) >= $param;
    }

    public function validateAlphabetic($value)
    {
        return boolval(preg_match('/^[a-zа-яё]+$/ui', $value));
    }

    public function validateIsEmail($value)
    {
        return boolval(preg_match('/^[^@.]+@[^@.]+\.[^@.]+$/u', $value));
    }
}
