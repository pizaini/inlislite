<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use \common\models\base\Users as BaseUsers;

/**
 * This is the model class for table "users".
 */
class Users extends BaseUsers implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $currentPassword;
    public $newPassword;
    public $confirmNewPassword;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['IsActive', 'default', 'value' => self::STATUS_ACTIVE],
            ['IsActive', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            [['currentPassword', 'newPassword', 'confirmNewPassword'], 'required'],
            [['currentPassword'], 'validateCurrentPassword'],

            [['newPassword', 'confirmNewPassword'], 'string','min'=>6],
            [['newPassword', 'confirmNewPassword'], 'filter','filter'=>'trim'],
            [['confirmNewPassword'], 'compare','compareAttribute'=>'newPassword','message'=>'Password Tidak Sama'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'IsActive' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'IsActive' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'IsActive' => self::STATUS_ACTIVE,
        ]);
    }


    /**
     * Returns user role name according to RBAC
     * @return string
     */
    public function getRoleName()
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        if (!$roles) {
            return null;
        }

        reset($roles);
        /* @var $role \yii\rbac\Role */
        $role = current($roles);

        return $role->name;
    }
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return strtoupper($this->password) === strtoupper(sha1($password)) ;
        //return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        $this->password = sha1($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * [removePasswordResetToken description]
     * @return [type] [description]
     */
    public function validateCurrentPassword()
    {
        if (!$this->verifyPassword($this->currentPassword)) {
            $this->addError("currentPassword",Yii::t('app','Password lama salah'));
        }

    }

    /**
     * [verifyPassword description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function verifyPassword($password)
    {
        // $dbPassword = static::findOne(['username' => Yii::$app->user->identity->username])->password_hash;
        // return Yii::$app->security->validatePassword($password, $dbPassword);

        $dbPassword = static::findOne(['username' => Yii::$app->user->identity->username])->password;

        return strtolower($dbPassword) === strtolower(sha1($password)) ;

        // return Yii::$app->security->validatePassword($password, $dbPassword);
    }

    public function attributeLabels() {
        return [
            'ID' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'username'),
            'Fullname' => Yii::t('app', 'Fullname'),
            'currentPassword' => Yii::t('app', 'Password lama'),
            'newPassword' => Yii::t('app', 'Password baru'),
            'confirmNewPassword' => Yii::t('app', 'Ulangi password baru'),
        ];
    }

}
