<?php
namespace PricklyNut\NoxChallenge\Entity;

class User
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $surname;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $salt;
    /**
     * @var string
     */
    private $hash;
    /**
     * @var array
     */
    private $errors = array();
    /**
     * @var array
     */
    private $disabledFields = array();

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Human readable form labels
     * @return array
     */
    public function getLabels()
    {
        return array(
            'ru' => array(
                'name' => "Имя",
                'surname' => "Фамилия",
                'email' => "Эл. почта",
            ),
            'en' => array(
                'name' => "First name",
                'surname' => "Last name",
                'email' => "Email",
            ),
        );
    }

    /**
     * Adds an error message to the form
     * @param string $field
     * @param string $error
     */
    public function addError($field, $error)
    {
        $this->errors[$field][] = $error;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function getRules()
    {
        $basicRules = array(
            'name' => array(
                'notEmpty' => true,
                'maxLength' => 50,
                'alphabetic' => true,
            ),
            'surname' => array(
                'notEmpty' => true,
                'maxLength' => 50,
                'alphabetic' => true,
            ),
            'email' => array(
                'notEmpty' => true,
                'maxLength' => 255,
                'isEmail' => true,
            ),
        );

        if ($this->disabledFields) {
            foreach ($this->disabledFields as $disabled) {
                $basicRules[$disabled] = null;
            }
        }
        return $basicRules;
    }

    /**
     * Some fields are involved not in every form, so need to disable their validation
     * @param array $fields
     */
    public function disableFieldsValidation(array $fields) {
        foreach ($fields as $field) {
            $this->disabledFields[] = $field;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }
}
