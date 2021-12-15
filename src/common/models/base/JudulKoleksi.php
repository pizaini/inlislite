<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "judul_koleksi".
 *
 * @property integer $no
 * @property string $judul_pernyataan
 * @property string $ed_cet
 * @property string $kota
 * @property string $penerbit
 * @property integer $tahun
 * @property string $no_induk
 * @property string $no_class
 * @property string $no_panggil
 * @property string $pengarang
 * @property string $kolasi
 * @property string $catatan
 * @property string $bibliografi
 * @property string $indeks
 * @property string $isbn
 * @property string $subjek1
 * @property string $subjek2
 * @property string $subjek3
 * @property string $subjek4
 * @property string $tet1
 * @property string $tet2
 * @property string $tet3
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class JudulKoleksi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'judul_koleksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no', 'judul_pernyataan'], 'required'],
            [['no', 'tahun', 'CreateBy', 'UpdateBy'], 'integer'],
            [['kolasi'], 'string'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['judul_pernyataan', 'penerbit', 'pengarang', 'catatan', 'bibliografi', 'indeks', 'subjek1', 'subjek2', 'subjek3', 'subjek4', 'tet1', 'tet2', 'tet3'], 'string', 'max' => 255],
            [['ed_cet', 'kota', 'no_induk', 'isbn', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['no_class', 'no_panggil'], 'string', 'max' => 50],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => Yii::t('app', 'No'),
            'judul_pernyataan' => Yii::t('app', 'Judul Pernyataan'),
            'ed_cet' => Yii::t('app', 'Ed Cet'),
            'kota' => Yii::t('app', 'Kota'),
            'penerbit' => Yii::t('app', 'Penerbit'),
            'tahun' => Yii::t('app', 'Tahun'),
            'no_induk' => Yii::t('app', 'No Induk'),
            'no_class' => Yii::t('app', 'No Class'),
            'no_panggil' => Yii::t('app', 'No Panggil'),
            'pengarang' => Yii::t('app', 'Pengarang'),
            'kolasi' => Yii::t('app', 'Kolasi'),
            'catatan' => Yii::t('app', 'Catatan'),
            'bibliografi' => Yii::t('app', 'Bibliografi'),
            'indeks' => Yii::t('app', 'Indeks'),
            'isbn' => Yii::t('app', 'Isbn'),
            'subjek1' => Yii::t('app', 'Subjek1'),
            'subjek2' => Yii::t('app', 'Subjek2'),
            'subjek3' => Yii::t('app', 'Subjek3'),
            'subjek4' => Yii::t('app', 'Subjek4'),
            'tet1' => Yii::t('app', 'Tet1'),
            'tet2' => Yii::t('app', 'Tet2'),
            'tet3' => Yii::t('app', 'Tet3'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
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
