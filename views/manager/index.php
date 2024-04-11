<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = Yii::t('imagemanager', 'Image manager');

?>

<style>
    #hiddenFileInput {
        position: absolute;
        left: -9999px;
    }
</style>

<div class="dialog__inner-wrapper" id="module-imagemanager">
	<header class="dialog__header">
		<div class="h1 dialog__heading">Image manager</div>
		<form method="POST" action="<?= \yii\helpers\Url::to(['manager/upload']) ?>">
			<input type="file" id="hiddenFileInput" name="imagemanagerFiles[]" multiple accept="image/*">
			<button type="button" class="button dbd-dialog__upload-button" id="uploadTrigger">
							<span class="icon">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								    <path
										    d="M21 14H20.25H21ZM21 15H21.75H21ZM15 21V20.25V21ZM9 21V21.75V21ZM3 15H2.25H3ZM3 14H3.75H3ZM7.21783 20.9384L7.1005 21.6792L7.1005 21.6792L7.21783 20.9384ZM3.06156 16.7822L3.80232 16.6648L3.06156 16.7822ZM20.9384 16.7822L21.6792 16.8995V16.8995L20.9384 16.7822ZM16.7822 20.9384L16.8995 21.6792L16.8995 21.6792L16.7822 20.9384ZM20.6 10.5496C20.3513 10.2184 19.8811 10.1516 19.5499 10.4003C19.2187 10.6491 19.1519 11.1192 19.4007 11.4504L20.6 10.5496ZM4.59931 11.4504C4.84808 11.1192 4.78126 10.6491 4.45007 10.4003C4.11888 10.1516 3.64873 10.2184 3.39996 10.5496L4.59931 11.4504ZM11.25 17C11.25 17.4142 11.5858 17.75 12 17.75C12.4142 17.75 12.75 17.4142 12.75 17H11.25ZM12 4H12.75H12ZM7.41232 6.53403C7.15497 6.8586 7.20946 7.33034 7.53403 7.58768C7.8586 7.84503 8.33034 7.79054 8.58768 7.46597L7.41232 6.53403ZM9.39785 5.23703L8.81016 4.77106L9.39785 5.23703ZM14.6022 5.23703L15.1898 4.77106L14.6022 5.23703ZM15.4123 7.46597C15.6697 7.79054 16.1414 7.84503 16.466 7.58769C16.7905 7.33034 16.845 6.8586 16.5877 6.53403L15.4123 7.46597ZM11.7493 3.01989L11.6313 2.27923H11.6313L11.7493 3.01989ZM12.2507 3.01989L12.3687 2.27923H12.3687L12.2507 3.01989ZM12 3V2.25V3ZM20.25 14V15H21.75V14H20.25ZM15 20.25L9 20.25V21.75L15 21.75V20.25ZM3.75 15L3.75 14H2.25L2.25 15H3.75ZM9 20.25C8.04233 20.25 7.65082 20.2477 7.33515 20.1977L7.1005 21.6792C7.56216 21.7523 8.09965 21.75 9 21.75V20.25ZM2.25 15C2.25 15.9003 2.24767 16.4378 2.32079 16.8995L3.80232 16.6648C3.75233 16.3492 3.75 15.9577 3.75 15H2.25ZM7.33515 20.1977C5.51661 19.9096 4.09035 18.4834 3.80232 16.6648L2.32079 16.8995C2.71048 19.3599 4.64012 21.2895 7.1005 21.6792L7.33515 20.1977ZM20.25 15C20.25 15.9577 20.2477 16.3492 20.1977 16.6648L21.6792 16.8995C21.7523 16.4378 21.75 15.9003 21.75 15H20.25ZM15 21.75C15.9003 21.75 16.4378 21.7523 16.8995 21.6792L16.6648 20.1977C16.3492 20.2477 15.9577 20.25 15 20.25V21.75ZM20.1977 16.6648C19.9096 18.4834 18.4834 19.9096 16.6648 20.1977L16.8995 21.6792C19.3599 21.2895 21.2895 19.3599 21.6792 16.8995L20.1977 16.6648ZM21.75 14C21.75 12.7064 21.3219 11.5106 20.6 10.5496L19.4007 11.4504C19.9342 12.1607 20.25 13.0424 20.25 14H21.75ZM3.75 14C3.75 13.0424 4.06583 12.1607 4.59931 11.4504L3.39996 10.5496C2.67806 11.5106 2.25 12.7064 2.25 14H3.75ZM12.75 17L12.75 4H11.25L11.25 17H12.75ZM8.58768 7.46597L9.98553 5.703L8.81016 4.77106L7.41232 6.53403L8.58768 7.46597ZM14.0145 5.703L15.4123 7.46597L16.5877 6.53403L15.1898 4.77106L14.0145 5.703ZM9.98553 5.703C10.5543 4.98568 10.9418 4.49884 11.2682 4.17113C11.5913 3.84678 11.7612 3.77747 11.8673 3.76055L11.6313 2.27923C11.0582 2.37055 10.6096 2.70685 10.2055 3.11252C9.80475 3.51484 9.35616 4.08245 8.81016 4.77106L9.98553 5.703ZM15.1898 4.77106C14.6438 4.08245 14.1953 3.51484 13.7945 3.11252C13.3904 2.70685 12.9418 2.37055 12.3687 2.27923L12.1327 3.76055C12.2388 3.77747 12.4087 3.84678 12.7318 4.17113C13.0582 4.49884 13.4457 4.98568 14.0145 5.703L15.1898 4.77106ZM11.8673 3.76055C11.9117 3.75348 11.9559 3.75 12 3.75V2.25C11.8766 2.25 11.7534 2.25978 11.6313 2.27923L11.8673 3.76055ZM12 3.75C12.0441 3.75 12.0883 3.75348 12.1327 3.76055L12.3687 2.27923C12.2466 2.25978 12.1234 2.25 12 2.25V3.75ZM12.75 4V3H11.25V4H12.75Z"
										    fill="currentColor"/>
								</svg>
							</span>
				Upload
			</button>
		</form>
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
      <?php endif; ?>
		<div class="img-manager-selected">
			<picture class="img-manager__image-wrapper">
				<img src="#" alt="">
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
				<div class="img-manager-selected__info created"></div>
				<div class="img-manager-selected__info fileSize"></div>
				<div class="img-manager-selected__info dimensions">
					<span class="dimension-width"></span>
					x
					<span class="dimension-height"></span>
				</div>
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
				<button class="dbd-button dbd-button--text crop-image-item">
              <span class="icon">
								<svg style="transform: translateY(2px)" width="17" height="17" viewBox="0 0 17 17" fill="none"
								     xmlns="http://www.w3.org/2000/svg">
								    <path
										    d="M1.8335 2.29537C1.41928 2.29537 1.0835 2.63116 1.0835 3.04537C1.0835 3.45959 1.41928 3.79537 1.8335 3.79537V2.29537ZM15.1668 14.7045C15.581 14.7045 15.9168 14.3687 15.9168 13.9545C15.9168 13.5402 15.581 13.2045 15.1668 13.2045V14.7045ZM3.79562 1.83325C3.79562 1.41904 3.45983 1.08325 3.04562 1.08325C2.6314 1.08325 2.29562 1.41904 2.29562 1.83325H3.79562ZM13.2047 15.1666C13.2048 15.5808 13.5406 15.9166 13.9548 15.9166C14.369 15.9166 14.7048 15.5808 14.7047 15.1666L13.2047 15.1666ZM4.16683 12.8333L4.69716 13.3636H4.69716L4.16683 12.8333ZM15.3638 2.69692C15.6567 2.40402 15.6567 1.92915 15.3638 1.63626C15.0709 1.34336 14.5961 1.34336 14.3032 1.63626L15.3638 2.69692ZM3.04562 3.79537H7.28804V2.29537H3.04562V3.79537ZM13.2047 9.71204V13.9545H14.7047V9.71204H13.2047ZM13.9547 13.2045H9.71228V14.7045H13.9547V13.2045ZM3.79562 7.2878V3.04537H2.29562V7.2878H3.79562ZM9.71228 13.2045C8.11973 13.2045 7.00077 13.2029 6.15465 13.0891C5.3301 12.9783 4.87784 12.7734 4.55226 12.4478L3.4916 13.5085C4.14233 14.1592 4.9639 14.4425 5.95477 14.5757C6.92408 14.7061 8.16214 14.7045 9.71228 14.7045V13.2045ZM2.29562 7.2878C2.29562 8.83794 2.29402 10.076 2.42434 11.0453C2.55756 12.0362 2.84087 12.8578 3.4916 13.5085L4.55226 12.4478C4.22668 12.1222 4.02183 11.67 3.91097 10.8454C3.79721 9.99931 3.79562 8.88035 3.79562 7.2878H2.29562ZM7.28804 3.79537C8.88059 3.79537 9.99956 3.79697 10.8457 3.91072C11.6702 4.02158 12.1225 4.22644 12.4481 4.55201L13.5087 3.49135C12.858 2.84062 12.0364 2.55732 11.0456 2.4241C10.0762 2.29378 8.83819 2.29537 7.28804 2.29537V3.79537ZM14.7047 9.71204C14.7047 8.16189 14.7063 6.92384 14.576 5.95453C14.4428 4.96365 14.1595 4.14209 13.5087 3.49135L12.4481 4.55201C12.7736 4.87759 12.9785 5.32986 13.0894 6.1544C13.2031 7.00052 13.2047 8.11949 13.2047 9.71204H14.7047ZM1.8335 3.79537H3.04562V2.29537H1.8335V3.79537ZM15.1668 13.2045H13.9547V14.7045H15.1668V13.2045ZM2.29562 1.83325V3.04537H3.79562V1.83325H2.29562ZM14.7047 15.1666L14.7047 13.9544L13.2047 13.9545L13.2047 15.1666L14.7047 15.1666ZM4.69716 13.3636L15.3638 2.69692L14.3032 1.63626L3.6365 12.3029L4.69716 13.3636ZM3.6365 12.3029L3.4916 12.4478L4.55226 13.5085L4.69716 13.3636L3.6365 12.3029Z"
										    fill="currentColor"/>
								</svg>
              </span>
					Crop
				</button>
				<button class="dbd-button dbd-button--red dbd-button--text delete-image-item">
              <span class="icon">
								<svg style="transform: translateY(2px);" width="16" height="16" viewBox="0 0 16 16" fill="none"
								     xmlns="http://www.w3.org/2000/svg">
								    <g clip-path="url(#clip0_1_13133)">
								        <path fill-rule="evenodd" clip-rule="evenodd"
								              d="M12.57 10.3437L12.7723 8.89021C12.8345 8.443 12.8914 8.03416 12.942 7.65913C13.0459 6.88843 13.0979 6.50307 12.8675 6.23949C12.6371 5.9759 12.241 5.9759 11.4489 5.9759H4.04505C3.25297 5.9759 2.85693 5.9759 2.62651 6.23949C2.39608 6.50307 2.44805 6.88843 2.55198 7.65913C2.60257 8.03428 2.65943 8.44283 2.72169 8.89021L2.92397 10.3437C3.14382 11.9235 3.25374 12.7133 3.48682 13.3477C3.92267 14.534 4.67609 15.4176 5.57535 15.7971C6.05623 16 6.61983 16 7.747 16C8.87416 16 9.43776 16 9.91864 15.7971C10.8179 15.4176 11.5713 14.534 12.0072 13.3477C12.2403 12.7133 12.3502 11.9235 12.57 10.3437ZM6.78313 7.51807C6.78313 7.19868 6.52421 6.93976 6.20482 6.93976C5.88543 6.93976 5.62651 7.19868 5.62651 7.51807V13.6867C5.62651 14.0061 5.88543 14.2651 6.20482 14.2651C6.52421 14.2651 6.78313 14.0061 6.78313 13.6867V7.51807ZM9.86747 7.51807C9.86747 7.19868 9.60855 6.93976 9.28916 6.93976C8.96976 6.93976 8.71084 7.19868 8.71084 7.51807V13.6867C8.71084 14.0061 8.96976 14.2651 9.28916 14.2651C9.60855 14.2651 9.86747 14.0061 9.86747 13.6867V7.51807Z"
								              fill="currentColor"/>
								        <path
										        d="M7.74699 0C5.72416 0 4.08434 1.63982 4.08434 3.66265V3.85542H1.57831C1.25892 3.85542 1 4.11434 1 4.43373C1 4.75313 1.25892 5.01205 1.57831 5.01205H13.9157C14.2351 5.01205 14.494 4.75313 14.494 4.43373C14.494 4.11434 14.2351 3.85542 13.9157 3.85542H11.4096V3.66265C11.4096 1.63982 9.76981 0 7.74699 0Z"
										        fill="currentColor"/>
								    </g>
								    <defs>
								        <clipPath id="clip0_1_13133">
								            <rect width="16" height="16" fill="white"/>
								        </clipPath>
								    </defs>
								</svg>
              </span>
					Delete
				</button>
				<button class="button pick-image-item">Select</button>
			</div>
		</div>
	</div>
    <?php Pjax::end(); ?>
</div>


<script>
  document.getElementById('uploadTrigger').addEventListener('click', function () {
    document.getElementById('hiddenFileInput').click()
  })

  $('#hiddenFileInput').on('change', function () {
    var fileData = $(this).prop('files')
    var formData = new FormData()

	  Array.from(fileData).forEach(function (file) {
		  formData.append('imagemanagerFiles[]', file)
	  })
    // formData.append('imagemanagerFiles[]', fileData)

    console.log(formData)

    // Отправляем AJAX запрос на сервер
    $.ajax({
      url: '<?= \yii\helpers\Url::to(['manager/upload']) ?>',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        console.log(response)

        imageManagerModule.uploadSuccess(response.imagemanagerFiles)
      },
      error: function () {
        console.log('error')
      }
    })
  })
</script>