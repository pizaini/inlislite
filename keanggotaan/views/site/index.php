<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use inliscore\adminlte\widgets\Box;
$this->title = Yii::t('app', 'Keanggotaan Inlislite');


?>
<div class="site-index">
    <div class="row">
        <div class="col-md-12">


            <div class="col-md-3 col-sm-6 col-xs-12">

                <?php
                $count = (new \yii\db\Query())
                    ->from('collectionloanitems')
                    ->join('INNER JOIN', 'members', 'members.ID = collectionloanitems.member_id')
                    ->where(['members.MemberNo'=>Yii::$app->user->identity->NoAnggota])
                    ->count();

                echo \inliscore\adminlte\widgets\SmallBox::widget([
                    'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_LBLUE,
                    'head'=>$count,
                    'text'=> Yii::t('app', 'Peminjaman'),
                    'icon'=>'fa fa-book',
                    'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
                    'footer_link'=>'#'
                ]);?>


            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">

                <?php
                $count = (new \yii\db\Query())
                    ->from('bacaditempat')
                    ->join('INNER JOIN', 'members', 'members.ID = bacaditempat.member_id')
                    ->where(['members.MemberNo'=>Yii::$app->user->identity->NoAnggota])
                    ->count();

                echo \inliscore\adminlte\widgets\SmallBox::widget([
                    'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_AQUA,
                    'head'=>$count,
                    'text'=> Yii::t('app', 'Baca Di Tempat'),
                    'icon'=>'fa fa-book',
                    'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
                    'footer_link'=>'#'
                ]);?>


            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">

                <?php
                $count = (new \yii\db\Query())
                    ->from('pelanggaran')
                    ->join('INNER JOIN', 'members', 'members.ID = pelanggaran.member_id')
                    ->where(['members.MemberNo'=>Yii::$app->user->identity->NoAnggota])
                    ->count();

                echo \inliscore\adminlte\widgets\SmallBox::widget([
                    'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_ORANGE,
                    'head'=>$count,
                    'text'=> Yii::t('app', 'Pelanggaran'),
                    'icon'=>'fa fa-book',
                    'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
                    'footer_link'=>'#'
                ]);?>


            </div>

        </div>
    </div>

    <div class="body-content">
         <?php
            Box::begin([
               'type'=>Box::TYPE_DEFAULT,
               'solid'=>TRUE,
               'title'=>'Statistik',
               'withBorder'=>true,
               'collapse_remember'=>'false',
               'collapse'=>false
            ]);
        ?>
        <div class="chart" id="chart" style="height: 300px;"></div>
        <?php 
            Box::end();
        ?>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var dataChart = "";
        $.ajax({
            type: 'POST',
            url: "<?=Yii::$app->urlManager->createUrl(['site/get-chart-data'])?>"
,
            data: '{ AnggotaNo : <?= Yii::$app->user->identity->NoAnggota ?> }',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (data) {
                //var dataChart = data;
                //alert( jQuery.parseJSON(data.));


                // AREA CHART
               var area = new Morris.Area({
                    element: 'chart',
                    resize: true,
                    data: data,
                    /*data: [
                        {y: '2011 Q1', item1: 2666, item2: 2666},
                        {y: '2011 Q2', item1: 2778, item2: 2294},
                        {y: '2011 Q3', item1: 4912, item2: 1969},
                        {y: '2011 Q4', item1: 3767, item2: 3597},
                        {y: '2012 Q1', item1: 6810, item2: 1914},
                        {y: '2012 Q2', item1: 5670, item2: 4293},
                        {y: '2012 Q3', item1: 4820, item2: 3795},
                        {y: '2012 Q4', item1: 15073, item2: 5967},
                        {y: '2013 Q1', item1: 10687, item2: 4460},
                        {y: '2013 Q2', item1: 8432, item2: 5713}
                    ],*/
                    xkey: 'internum',
                    ykeys: ['CountPeminjaman', 'CountBacaDitempat', 'CountPelanggaran'],
                    labels: ['Peminjaman', 'Baca di Tempat', 'Pelanggaran'],
                    lineColors: ['#a0d0e0', '#3c8dbc'],
                    hideHover: 'auto'
                });

            },
            error:function(){
                //alert('ad');
            }
        });

    });




</script>
