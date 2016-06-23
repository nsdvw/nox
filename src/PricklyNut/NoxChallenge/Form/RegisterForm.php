<?php
namespace PricklyNut\NoxChallenge\Form;

use PricklyNut\NoxChallenge\Validator\Validator;

class RegisterForm extends BaseAuthForm
{
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
                'password' => "Пароль",
                'submit' => "Зарегистрироваться",
            ),
            'en' => array(
                'name' => "First name",
                'surname' => "Last name",
                'email' => "Email",
                'password' => "Password",
                'submit' => "Sign up"
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

        return $this->loginManager->isEmailFree($this);
    }

    public function handleRequest()
    {
        $submittedData = isset($_POST['registerForm']) ? $_POST['registerForm'] : null;
        if ($submittedData) {
            $this->setSubmitted(true);
            $this->populateModel($submittedData);
            $this->fillExtraFields($submittedData);
        }
    }
}
