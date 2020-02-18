<?php

namespace app\models;
use yii\web\IdentityInterface;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $nickname
 * @property int|null $balance
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balance', 'nickname'], 'required'],
            [['nickname'], 'string', 'max' => 255],
            [['nickname'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => 'Nickname',
            'balance' => 'Balance',
        ];
    }
 
    
    public static function findIdentity($id)
	{
		return User::findOne($id);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getAuthKey()
	{

	}

	public function validateAuthKey($key)
	{

	}

	public static function findIdentityByAccessToken($token, $type=null)
	{

	}

	public static function findByUsername($username)
	{
        if(User::find()->where(['nickname' => $username])->one()){
            return User::find()->where(['nickname' => $username])->one();
        } else {
            return User::createNewUser($username);
        }
		
	}
    
    public static function createNewUser($username)
    {
        $user = new User();
        $user->nickname = $username;
        $user->balance = 0;
        $user->save();
        return User::find()->where(['nickname' => $username])->one();
    }

	public function validatePassword ($password)
	{
		return ($this->password == $password) ? true : false;
	}
}
