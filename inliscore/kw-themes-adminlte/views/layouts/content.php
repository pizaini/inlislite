<?php
use inliscore\adminlte\widgets\Breadcrumbs;
use inliscore\adminlte\widgets\Box;
use inliscore\adminlte\widgets\Alert;
use inliscore\adminlte\widgets\AlertKartik;
use yii\helpers\Html;

?>


<div class="content-wrapper">

    <section class="content-header">
        <h1><?=$this->title?></h1>
        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
   
    <section class="content">
        <?php
            Box::begin([
               'type'=>Box::TYPE_PRIMARY,
               'solid'=>false,
               //'title'=>'Header',
               'withBorder'=>true,
               'collapse_remember'=>'false',
               'collapse'=>false
            ]);
        ?>
         <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
                    <?php
                    echo \kartik\widgets\Growl::widget([
                        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
                        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
                        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
                        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
                        'showSeparator' => true,
                        //'delay' => 1, //This delay is how long before the message shows
                        'pluginOptions' => [
                            //'showProgressbar' => true,
                            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
                            'placement' => [
                                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                            ]
                        ]
                    ]);
                    ?>
                <?php endforeach; ?>
        <?= $content ?>


        <?php
            Box::end();
        ?>
    </section>


</div>

    <footer class="footer main-footer">
        <div class="container">
            <div class="pull-right hidden-sm" style="font-family: "Corbel", Arial, Helvetica, sans-serif;">
                <?=\Yii::$app->params['footerInfoRight'];?>
            </div>
            <?= yii::t('app',\Yii::$app->params['footerInfoLeft']); ?> &copy; <?= yii::t('app',\Yii::$app->params['year']); ?> <a href="http://inlislite.perpusnas.go.id" target="_blank"><?= yii::t('app','Perpustakaan Nasional Republik Indonesia') ?></a>

            
        </div> <!-- /.container -->
    </footer>