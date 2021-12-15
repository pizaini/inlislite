<?php

namespace common\models;

use Yii;
use \common\models\base\MemberFields as BaseMemberFields;

use common\models\MembersForm;
use common\models\MembersFormList;
use common\models\MembersOnlineForm;
use common\models\MembersOnlineFormEdit;
use common\models\MembersLoanForm;
use common\models\MembersInfoForm;
use common\models\MembersLoanreturnForm;

/**
 * This is the model class for table "member_fields".
 */
class MemberFields extends BaseMemberFields
{

     public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    /**
     * Function untuk mencari member_field_id berdasarkan jenis perpustakaan
     * dan id member field.
     *
     * @param  [int] $jenis_perpus [id jenis perpustakaan]
     * @param  [int] $id           [id member field]
     * @return [string]            [Member_Field_id]
     */
    public function getTrueFalse($jenis_perpus,$id)
    {
        $sql = MembersForm::find()->select('Member_Field_id')->where(['Member_Field_id' => $id,'Jenis_Perpustakaan_id' => $jenis_perpus])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }
        public function getDaftarAnggota($jenis_perpus,$id)
    {
        $sql = MembersFormList::find()->select('Member_Field_id')->where(['Member_Field_id' => $id,'Jenis_Perpustakaan_id' => $jenis_perpus])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }
            public function getFormAnggotaOnline($jenis_perpus,$id)
    {
        $sql = MembersOnlineForm::find()->select('Member_Field_id')->where(['Member_Field_id' => $id,'Jenis_Perpustakaan_id' => $jenis_perpus])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }
    
           public function getFormEditAnggotaOnline($jenis_perpus,$id)
    {
        $sql = MembersOnlineFormEdit::find()->select('Member_Field_id')->where(['Member_Field_id' => $id,'Jenis_Perpustakaan_id' => $jenis_perpus])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }
               public function getFormEntriPeminjaman($jenis_perpus,$id)
    {
        $sql = MembersLoanForm::find()->select('Member_Field_id')->where(['Member_Field_id' => $id,'Jenis_Perpustakaan_id' => $jenis_perpus])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }
    
                   public function getFormEntriPengembalian($jenis_perpus,$id)
    {
        $sql = MembersLoanreturnForm::find()->select('Member_Field_id')->where(['Member_Field_id' => $id,'Jenis_Perpustakaan_id' => $jenis_perpus])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }
                       public function getFormInfoAnggota($jenis_perpus,$id)
    {
        $sql = MembersInfoForm::find()->select('Member_Field_id')->where(['Member_Field_id' => $id,'Jenis_Perpustakaan_id' => $jenis_perpus])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }
    
}
