<?php
namespace keanggotaan\models;

use common\models\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $EmailAddress;
    public $EmailAddress2;
    public $MemberNo;
    public $Name;
    public $DateOfBirth;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmailAddress','MemberNo','Name','DateOfBirth','EmailAddress2'], 'filter', 'filter' => 'trim'],
            [['EmailAddress','MemberNo'], 'required'],
            ['EmailAddress', 'email'],
            //['EmailAddress2', 'email'],
            ['EmailAddress', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = \common\models\Membersonline::findOne([
            'Status' => \common\models\Membersonline::STATUS_ACTIVE,
            'EmailAddress' => $this->email,
        ]);

        if ($user) {
            if (!\common\models\Membersonline::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->EmailAddress)
                    ->setSubject('Password reset for ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}
