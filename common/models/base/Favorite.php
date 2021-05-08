<?php

namespace common\models\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "favorite".
 *
 * @property double $id
 * @property double $Catalog_Id
 * @property string $Member_Id
 * @property string $Createdate
 *
 * @property \common\models\Catalogs $catalog
 * @property \common\models\Members $member
 */
class Favorite extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'favorite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Catalog_Id'], 'number'],
            [['Createdate'], 'safe'],
            [['Member_Id'], 'string', 'max' => 50],
            [['Catalog_Id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['Catalog_Id' => 'ID']],
            [['Member_Id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['Member_Id' => 'MemberNo']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'Catalog_Id' => Yii::t('app', 'Catalog  ID'),
            'Member_Id' => Yii::t('app', 'Member  ID'),
            'Createdate' => Yii::t('app', 'Createdate'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(\common\models\Catalogs::className(), ['ID' => 'Catalog_Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(\common\models\Members::className(), ['MemberNo' => 'Member_Id']);
    }


    public function search($params)
    {
        $query = Favorite::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);       

        $query->andFilterWhere(['like', 'Member_id', $this->Member_Id]);
           

        return $dataProvider;
    }

   

/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
        \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'CreateDate',
                'updatedAtAttribute' => 'UpdateDate',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'CreateBy',
                'updatedByAttribute' => 'UpdateBy',
            ],
            [
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
        ];
    }


    
}
