<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
use frontend\helpers\grid\GridView; 
use yii\web\View;
use app\models\Cooperativetype;
use app\models\Parcel;
use app\models\Farms;
use app\models\Loan;
use app\models\Lockedinfo;
use app\models\Auditprocess;
use app\models\Reviewprocess;
use app\models\Session;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\farms */

?>
<div class="farms-view">
 
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        农场转让信息
                    </h3>
                </div>
                <div class="box-body">
	<p>

        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </p>
    <div class="row">
        <div class="col-md-4">

        </div>
        <!-- /.col -->
        <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
              <h3 class="widget-user-username">Alexander Pierce</h3>
              <h5 class="widget-user-desc">Founder &amp; CEO</h5>
            </div>

            <div class="box-footer">
              <div class="row">
                <div class="col-sm-6 border-right">
                  <div class="description-block">
                    <h2 >整体转让</h2>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                  <div class="description-block">
                    <h2>部分转让</h2>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">
        
        </div>
        <!-- /.col -->
      </div>
      <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
</section>
</div>

