<?php
namespace PricklyNut\NoxChallenge\Service;

use PricklyNut\NoxChallenge\Entity\User;
use PricklyNut\NoxChallenge\Form\BaseAuthForm;
use PricklyNut\NoxChallenge\Helper\Generator;
use PricklyNut\NoxChallenge\Mapper\UserMapper;

class LoginManager
{
    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var string
     */
    protected $language;

    /**
     * Error messages
     * @return array
     */
    protected function getErrors()
    {
        return array(
            'ru' => array(
                'notFound' => "Пользователь не найден",
                'wrongPassword' => "Неправильный пароль",
                'emailOccupied' => "С этой почты уже была регистрация",
            ),
            'en' => array(
                'notFound' => "User not found",
                'wrongPassword' => "Wrong password",
                'emailOccupied' => "This email is occupied",
            ),
        );
    }

    public function __construct(UserMapper $userMapper, $language)
    {
        $this->userMapper = $userMapper;
        $this->language = $language;
    }

    public function getLoggedUser()
    {
        $id = isset($_COOKIE['id']) ? $_COOKIE['id'] : null;
        $hash = isset($_COOKIE['hash']) ? $_COOKIE['hash'] : null;
        if (!$id or !$hash) {
            return null;
        }
        $dbRecord = $this->userMapper->findById($id);
        if (!$dbRecord) {
            return null;
        }
        if ($dbRecord->getHash() === $hash) {
            return $dbRecord;
        }
        return null;
    }

    /**
     * @param BaseAuthForm $form
     * @return bool
     */
    public function findUserAndCheckPassword(BaseAuthForm $form)
    {
        $email = $form->getUser()->getEmail();
        $dbRecord = $this->userMapper->findByEmail($email);
        if (!$dbRecord) {
            $message = $this->getErrors()[$this->language]['notFound'];
            $form->addError('email', $message);
            return false;
        }

        $dbSalt = $dbRecord->getSalt();
        $dbHash = $dbRecord->getHash();
        $newSaltedHash = Generator::generateSaltedHash($dbSalt, $form->getPassword());

        if ($newSaltedHash != $dbHash) {
            $message = $this->getErrors()[$this->language]['wrongPassword'];
            $form->addError('password', $message);
            return false;
        }
        $form->getUser()->setId($dbRecord->getId());
        $form->getUser()->setHash($dbRecord->getHash());
        return true;
    }

    public function isEmailFree(BaseAuthForm $form)
    {
        $email = $form->getUser()->getEmail();
        $dbRecord = $this->userMapper->findByEmail($email);
        if ($dbRecord) {
            $message = $this->getErrors()[$this->language]['emailOccupied'];
            $form->addError('email', $message);
            return false;
        }
        return true;
    }

    /**
     * @param User $user
     * @param bool $remember
     */
    public function login(User $user, $remember = false)
    {
        if ($remember === true) {
            $time = time() + 3600*24*7;
        } else {
            $time = 0;
        }
        $path = '/';
        setcookie('id', $user->getId(), $time, $path);
        setcookie('hash', $user->getHash(), $time, $path);
    }
}
