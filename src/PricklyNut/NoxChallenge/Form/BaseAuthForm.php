<?php
namespace PricklyNut\NoxChallenge\Form;

use PricklyNut\NoxChallenge\Entity\User;
use PricklyNut\NoxChallenge\Service\LoginManager;
use PricklyNut\NoxChallenge\Validator\Validator;

abstract class BaseAuthForm
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var LoginManager
     */
    protected $loginManager;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var bool
     */
    protected $rememberMe = false;

    /**
     * @var bool
     */
    protected $submitted = false;

    /**
     * @var array
     */
    protected $errors = array();

    public function __construct(LoginManager $loginManager, User $user)
    {
        $this->loginManager = $loginManager;
        $this->user = $user;
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function getRules()
    {
        return array(
            'password' => array(
                'notEmpty' => true,
                'maxLength' => 50,
                'minLength' => 5
            ),
        );
    }

    /**
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
        return array_merge($this->errors, $this->user->getErrors());
    }

    /**
     * @return boolean
     */
    public function isSubmitted()
    {
        return $this->submitted;
    }

    /**
     * @param boolean $submitted
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;
    }

    /**
     * @return LoginManager
     */
    public function getLoginManager()
    {
        return $this->loginManager;
    }

    /**
     * @param LoginManager $loginManager
     */
    public function setLoginManager($loginManager)
    {
        $this->loginManager = $loginManager;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function getRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * @param bool $rememberMe
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
    }

    public function isValid(Validator $validator)
    {
        $validator->validate($this->user);
        $this->validateExtraFields($validator);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param Validator $validator
     * @return bool
     */
    protected function validateExtraFields(Validator $validator)
    {
        return $validator->validate($this);
    }

    protected function populateModel(array $data)
    {
        $model = $this->user;

        $name = isset($data['name']) ? $data['name'] : null;
        $model->setName($name);
        $surname = isset($data['surname']) ? $data['surname'] : null;
        $model->setSurname($surname);

        $email = isset($data['email']) ? $data['email'] : null;
        $model->setEmail($email);
    }

    protected function fillExtraFields(array $data)
    {
        $password = isset($data['password']) ? $data['password'] : null;
        $this->setPassword($password);
        $rememberMe = isset($data['rememberMe']) ? boolval($data['rememberMe'])
            : false;
        $this->setRememberMe($rememberMe);
    }
}
