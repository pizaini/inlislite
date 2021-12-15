<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$this->context->layout = 'error';

?>
<div class="error-template">
                <h1>
                    Oops!</h1>
                <h2><?= Html::encode($this->title) ?></h2>
                <div class="error-details">
                     <div class="alert alert-danger">
                        <?= nl2br(Html::encode($message)) ?>
                    </div>
                </div>
                <div class="error-actions">
                    <a href="#" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Take Me Home </a>
                    <a href="#" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-envelope"></span> Contact Support </a>
                </div>
</div>
