<?php
use yii\helpers\Html;
use yii\helpers\Url;

use inliscore\adminlte\widgets\Nav;
use inliscore\adminlte\widgets\NavBar;
use common\models\Members;
use common\components\MemberHelpers;
use common\components\Helpers;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<!-- <a href="index.php" class="logo">
    Add the class icon to your logo image or logo icon to add the margining
    KampoengWeb.com
</a> -->

<?php
NavBar::begin([
    'brandShortLabel' => Html::img(Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logoinlis.png'), ['alt'=>'some', 'class'=>'','height'=>'26px']),
    'brandLabel' =>  Yii::$app->config->get('NamaPerpustakaan').' <br/> ',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-static-top',
        'style'=>['height'=> '89px','background' => '#369']
    ],
    'containerOptions'=>[
        'style'=>['padding-top'=> '38px']
    ],

]);




if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {

    $memberID = Members::find()->where(['MemberNo'=>Yii::$app->user->identity->NoAnggota])->one();

    $photo = ($memberID->PhotoUrl) ? $memberID->PhotoUrl : $memberID->ID;


    $imgCheck = Yii::getAlias('@uploaded_files') . '/'.Yii::$app->params['pathFotoAnggota'].'/' . $photo;

    if (!file_exists($imgCheck)) {
        // No.Photo
        $image = '../../uploaded_files/'.Yii::$app->params['pathFotoAnggota'].'/nophoto.jpg?timestamp=' . rand();
    }else{
        $image = '../../uploaded_files/'.Yii::$app->params['pathFotoAnggota'].'/'. $photo .'?timestamp=' . rand();
    }








    
    echo $imgTemp;
    $menuItems[] = [
        'type'=>'user',
        'label' => '#',
        'url' => ['/tasks/index'],
        'logoutUrl' => ['/site/logout'],
        'logoutLabel' => 'Keluar',
        'profileUrl' => ['/user/change-password'],
        'profileLabel' => 'Ganti Password',

       /* 'historyUrl' => ['/setting/umum/user/my-history'],
        'historyLabel' => 'Histori',*/

        'image'=> $image,
        'username'=>Yii::$app->user->identity->NoAnggota,
        'position'=>MemberHelpers::getMemberByNoAnggota(Yii::$app->user->identity->NoAnggota)->Fullname,
        'join'=>
        'Masa Berlaku : '.
        Helpers::DateTimeToViewFormat(MemberHelpers::getMemberByNoAnggota(Yii::$app->user->identity->NoAnggota)->RegisterDate). ' s/d ' .  Helpers::DateTimeToViewFormat(MemberHelpers::getMemberByNoAnggota(Yii::$app->user->identity->NoAnggota)->EndDate),
        'items'=>[]
    ];
    /*
    $menuItems[] = [
        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',

        'linkOptions' => ['data-method' => 'post']
    ];*/
}
//$menuItems[] = ['type'=>'control','label' => '#', 'url' => '#'];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $menuItems,
]);
NavBar::end();
?>
