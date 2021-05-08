<?php
use yii\helpers\Html;
use yii\helpers\Url;

use inliscore\adminlte\widgets\Nav;
use inliscore\adminlte\widgets\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<!-- <a href="index.php" class="logo">
    Add the class icon to your logo image or logo icon to add the margining
    KampoengWeb.com
</a> -->
<!-- Header Navbar: style can be found in header.less -->
<?php
$location = Yii::$app->location->get();
if(empty($location)){
    return Yii::$app->response->redirect(Url::to(['login']));
}else{
    $id = \common\models\LocationLibrary::findOne($location);
}

?>
        <?php
            NavBar::begin([
                'brandShortLabel' => Html::img(Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logoinlis.png'), ['alt'=>'some', 'class'=>'','height'=>'26px']),
                // 'brandShortLabel' => Html::tag('p','PERPUSTAKAAN', ['alt'=>'some', 'class'=>'','style' => ['font-size'=>'22px','margin'=>'0px']]),
                'brandLabel' =>  Yii::$app->config->get('NamaPerpustakaan').' <br/> '.
                    $id->Address,
                'brandUrl' => Yii::$app->homeUrl,
             
              
                'options' => [
                    'class' => 'navbar-static-top',
                    // 'style'=>['height'=> '89px','background' => '#0ea043']
                    'style'=>['height'=> '89px','background' => '#369']
                ],
                'containerOptions'=>[
                     'style'=>['padding-top'=> '38px']
                ],
                
            ]);


            $menuItems = [
                //['encode'=>false,'label' => '<i class="fa fa-home"></i>', 'url' => ['/site/index']],
               /* ['type'=>'messages','label' => '#', 'url' => ['/messages/index'],'items'=>[
                    ['label' => 'Support Team', 'url' => ['/messages/view','id'=>1], 'image'=>Url::to($baseurl.'/img/user2-160x160.jpg'), 'description'=>'Why not buy a new awesome theme?', 'time'=>'5 mins'],
                    ['label' => 'Marketing Team', 'url' => ['/messages/view','id'=>2], 'image'=>Url::to($baseurl.'/img/user2-160x160.jpg'), 'description'=>'Why not buy a new awesome server?', 'time'=>'2 mins'],
                ]],
                ['type'=>'notifications','label' => '#', 'url' => ['/notifications/index'],'items'=>[
                    ['label' => 'Support Team', 'url' => ['/notifications/view','id'=>1], ],
                    ['label' => 'Support Team', 'url' => ['/notifications/view','id'=>3], ],
                    ['label' => 'Support Team', 'url' => ['/notifications/view','id'=>4], ],
                    ['label' => 'Support Team', 'url' => ['/notifications/view','id'=>2], ],
                    ['label' => 'Support Team', 'url' => ['/notifications/view','id'=>2], ],
                    ['label' => 'Support Team', 'url' => ['/notifications/view','id'=>2], ],
                    ['label' => 'Support Team', 'url' => ['/notifications/view','id'=>2], ],
                    ['label' => 'Support Team', 'url' => ['/notifications/view','id'=>2], ],
                ]],
                ['type'=>'tasks','label' => '#', 'url' => ['/tasks/index'],'items'=>[
                    ['label' => 'Migration', 'url' => ['/tasks/view','id'=>1], 'color'=>'aqua', 'percent'=>'10'],
                    ['label' => 'Backup', 'url' => ['/tasks/view','id'=>1], 'color'=>'red', 'percent'=>'75'],
                    ['label' => 'Analyze', 'url' => ['/tasks/view','id'=>1], 'color'=>'yellow', 'percent'=>'25'],
                    ['label' => 'Tutorial', 'url' => ['/tasks/view','id'=>1], 'color'=>'lime', 'percent'=>'50'],
                ]],
                ['label' => 'Page', 'url' => '#',
                    'items'=>[
                        ['label' => 'Contact', 'url' => ['/site/contact']],
                        ['label' => 'About', 'url' => ['/site/about']],
                    ]
                ],*/
            ];

            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'type'=>'user',
                    'label' => '#',
                    'url' => ['/tasks/index'],
                    'logoutUrl' => ['/site/logout'],
                    'logoutLabel' => yii::t('app','Keluar'),
                    'profileUrl' => ['/setting/umum/user/change-password'],
                    'profileLabel' => yii::t('app','Ganti Password'),
                    
                    'historyUrl' => ['/setting/umum/user/my-history'],
                    'historyLabel' => yii::t('app','Aktifitas'),

                    'image'=>Url::to($baseurl.'/img/icon-user-default.png'),
                    'username'=>Yii::$app->user->identity->username,
                    'position'=>'User',
                    'join'=>yii::t('app','Terdaftar sejak ').date('d-m-Y',Yii::$app->user->identity->created_at),
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
