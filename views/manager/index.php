<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = Yii::t('imagemanager', 'Image manager');

?>

<div class="dialog__inner-wrapper" id="module-imagemanager">
	<header class="dialog__header">
		<div class="h1 dialog__heading">Image manager</div>
<!--		<button class="button dbd-dialog__upload-button">-->
<!--          <span class="icon">-->
<!--            <svg width="1em" height="1em" fill="currentColor">-->
<!--              <use href="/svg/icons/sprite.svg#upload"></use>-->
<!--            </svg>-->
<!--          </span>-->
<!--			Upload-->
<!--		</button>-->

      <?= \kartik\file\FileInput::widget([
          'name'          => 'imagemanagerFiles[]',
          'id'            => 'imagemanager-files',
          'options'       => [
              'multiple' => true,
              'accept'   => 'image/*'
          ],
          'pluginOptions' => [
              'uploadUrl'             => \yii\helpers\Url::to(['manager/upload']),
              'allowedFileExtensions' => \Yii::$app->controller->module->allowedFileExtensions,
              'uploadAsync'           => false,
              'showPreview'           => false,
              'showRemove'            => false,
              'showUpload'            => false,
              'showCancel'            => false,
              'browseClass'           => 'button dbd-dialog__upload-button',
              //                  'browseIcon'            => '<i class="fa fa-upload"></i> ',
              'browseLabel'           => Yii::t('imagemanager', 'Upload')
          ],
          'pluginEvents'  => [
              "filebatchselected"      => "function(event, files){  $('.msg-invalid-file-extension').addClass('hide'); $(this).fileinput('upload'); }",
              "filebatchuploadsuccess" => "function(event, data, previewId, index) {
                      						imageManagerModule.uploadSuccess(data.jqXHR.responseJSON.imagemanagerFiles);
                      					}",
              "fileuploaderror"        => "function(event, data) { $('.msg-invalid-file-extension').removeClass('hide'); }",
          ],
      ]) ?>
	</header>

	<div class="form dbd-form dbd-form--filter dbd-form--image-manager-search">
		<label class="form__label form__label--text">
        <?= Html::textInput(
            'input-mediamanager-search',
            null,
            [
                'id'          => 'input-mediamanager-search',
                'class'       => 'form__text-input input-text',
                'placeholder' => Yii::t('imagemanager', 'Search') . '...'
            ]
        ) ?>
		</label>
	</div>

    <?php Pjax::begin([
        'id'      => 'pjax-mediamanager',
        'options' => [
            'class' => 'image-manager',
        ],
        'timeout' => '5000'
    ]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions'  => ['class' => 'item img-thumbnail'],
        'layout'       => "<div class='image-manager__images'>{items}</div> {pager}",
        'itemView'     => function ($model, $key, $index, $widget) {
            return $this->render("_item", ['model' => $model]);
        },
        'options'      => [
            'tag' => false, // setting 'tag' to false removes the wrapper tag
        ],
    ]) ?>


	<div class="image-manager__controls">
      <?php if ($isLimitReached) : ?>
				<div class="alert alert-warning">
            <?= Yii::t('imagemanager', 'You have reached the maximum number of images.') ?>
				</div>
      <?php elseif (Yii::$app->controller->module->canUploadImage): ?>
          <?= FileInput::widget([
              'name'          => 'imagemanagerFiles[]',
              'id'            => 'imagemanager-files',
              'options'       => [
                  'multiple' => true,
                  'accept'   => 'image/*'
              ],
              'pluginOptions' => [
                  'uploadUrl'             => Url::to(['manager/upload']),
                  'allowedFileExtensions' => \Yii::$app->controller->module->allowedFileExtensions,
                  'uploadAsync'           => false,
                  'showPreview'           => false,
                  'showRemove'            => false,
                  'showUpload'            => false,
                  'showCancel'            => false,
                  'browseClass'           => 'btn btn-primary btn-block',
//                  'browseIcon'            => '<i class="fa fa-upload"></i> ',
                  'browseLabel'           => Yii::t('imagemanager', 'Upload')
              ],
              'pluginEvents'  => [
                  "filebatchselected"      => "function(event, files){  $('.msg-invalid-file-extension').addClass('hide'); $(this).fileinput('upload'); }",
                  "filebatchuploadsuccess" => "function(event, data, previewId, index) {
                      						imageManagerModule.uploadSuccess(data.jqXHR.responseJSON.imagemanagerFiles);
                      					}",
                  "fileuploaderror"        => "function(event, data) { $('.msg-invalid-file-extension').removeClass('hide'); }",
              ],
          ]) ?>

      <?php endif; ?>

		<div class="img-manager-selected">
			<picture>
				<img src="#">
			</picture>
        <?php if (!$isLimitReached && false): ?>
					<div class="edit-buttons">
						<a href="#ad-block" class="btn btn-primary btn-block crop-image-item">
							<i class="fa fa-crop"></i>
							<span class="hidden-xs"><?= Yii::t('imagemanager', 'Crop') ?></span>
						</a>
					</div>
        <?php endif; ?>
			<div class="img-manager-selected__wrapper">
				<div class="img-manager-selected__name"></div>
				<div class="img-manager-selected__info"></div>
				<div class="img-manager-selected__info"></div>
				<div class="img-manager-selected__info"></div>
          <?php if (Yii::$app->controller->module->canRemoveImage && false): ?>
						<a href="#ad-block" class="btn btn-danger delete-image-item">
							<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                <?= Yii::t('imagemanager', 'Delete') ?>
						</a>
          <?php endif; ?>
			</div>
        <?php if ($viewMode === "iframe" && false): ?>
					<a href="#ad-block" class="btn btn-primary btn-block pick-image-item">
              <?= Yii::t('imagemanager', 'Select') ?>
					</a>
        <?php endif; ?>
			<div class="image-manager__controls-wrapper">
				<button class="dbd-button dbd-button--text">
              <span class="icon"><svg width="1em" height="1em" fill="currentColor">
                  <use href="/svg/icons/sprite.svg#crop"></use>
                </svg>
              </span>
					Crop
				</button>
				<button class="dbd-button dbd-button--red dbd-button--text">
              <span class="icon"><svg width="1em" height="1em" fill="currentColor">
                  <use href="/svg/icons/sprite.svg#delete"></use>
                </svg>
              </span>
					Delete
				</button>
				<button class="button">Select</button>
			</div>
		</div>
	</div>
    <?php Pjax::end(); ?>
</div>
</div>


