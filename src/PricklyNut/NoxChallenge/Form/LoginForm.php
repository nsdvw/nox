<?php
namespace PricklyNut\NoxChallenge\Form;

use PricklyNut\NoxChallenge\Validator\Validator;

class LoginForm extends BaseAuthForm
{
    /**
     * Human readable form labels
     * @return array
     */
    public function getLabels()
    {
        return array(
            'ru' => array(
                'email' => "Эл. почта",
                'password' => "Пароль",
                'rememberMe' => "Запомнить меня",
                'submit' => "Войти"
            ),
            'en' => array(
                'email' => "Email",
                'password' => "Password",
                'rememberMe' => "Remember me",
                'submit' => "Login"
            ),
        );
    }

    /**
     * @param Validator $validator
     * @return bool
     */
    public function isValid(Validator $validator)
    {
        parent::isValid($validator);

        if ($this->getErrors()) {
            return false;
        }

        return $this->loginManager->findUserAndCheckPassword($this);
    }

    /**
     * Find out, was the form submitted and populate its data
     */
    public function handleRequest()
    {
        $submittedData = isset($_POST['loginForm']) ? $_POST['loginForm'] : null;
        if ($submittedData) {
            $this->setSubmitted(true);
            $this->populateModel($submittedData);
            $this->fillExtraFields($submittedData);
        }
    }
}
