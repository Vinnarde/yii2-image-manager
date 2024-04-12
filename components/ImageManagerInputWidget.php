<?php

namespace noam148\imagemanager\components;

use noam148\imagemanager\assets\ImageManagerInputAsset;
use noam148\imagemanager\models\ImageManager;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

class ImageManagerInputWidget extends InputWidget
{

    /**
     * @var null|integer The aspect ratio the image needs to be cropped in (optional)
     */
    public $aspectRatio = null; //option info: https://github.com/fengyuanchen/cropper/#aspectratio

    /**
     * @var int Define the viewMode of the cropper
     */
    public $cropViewMode = 1; //option info: https://github.com/fengyuanchen/cropper/#viewmode

    /**
     * @var string Define placeholder for the input
     */
    public $placeholder = null;

    /**
     * @var bool Show a preview of the image under the input
     */
    public $showPreview = true;

    /**
     * @var bool Show a confirmation message when de-linking a image from the input
     */
    public $showDeletePickedImageConfirm = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //set language
        if (!isset(Yii::$app->i18n->translations['imagemanager'])) {
            Yii::$app->i18n->translations['imagemanager'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath'       => '@noam148/imagemanager/messages'
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        //default
        $ImageManager_id = null;
        $mImageManager   = null;
        $sFieldId        = null;
        //start input group
        $field = '<div class="dbd-form__upload-field">';
        $field .= '<label class="form__label form__label--text dbd-form__upload-label">';
        //set input fields
        if ($this->hasModel()) {
            //get field id
            $sFieldId     = Html::getInputId($this->model, $this->attribute);
            $sFieldNameId = $sFieldId . "_name";
            //get attribute name
            $sFieldAttributeName = Html::getAttributeName($this->attribute);
            //get filename from selected file
            $ImageManager_id       = $this->model->{$sFieldAttributeName};
            $ImageManager_fileName = null;
            $mImageManager         = ImageManager::findOne($ImageManager_id);
            if ($mImageManager !== null) {
                $ImageManager_fileName = $mImageManager->fileName;
            }
            //create field
            $field .= Html::textInput(
                $this->attribute,
                $ImageManager_fileName,
                [
                    'class'       => 'form__text-input input-text',
                    'id'          => $sFieldNameId,
                    'readonly'    => true,
                    'placeholder' => $this->placeholder
                ]
            );
            $field .= Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $field .= Html::textInput(
                $this->name . "_name",
                null,
                ['readonly' => true, 'placeholder' => $this->placeholder]
            );
            $field .= Html::hiddenInput($this->name, $this->value, $this->options);
        }

        $field .= '</label>';

        $deleteBtnSVG = <<<HTML
          <span class="icon"><svg width="1em" height="1em" fill="currentColor"><use href="/svg/icons/sprite.svg#cross"></use></svg></span>
        HTML;

        $uploadBtnSVG = <<<HTML
          <span class="icon"><svg width="1em" height="1em" fill="currentColor"><use href="/svg/icons/sprite.svg#upload"></use></svg></span>
        HTML;

        //end input group
        $sHideClass = $ImageManager_id === null ? 'hide' : '';
        $field      .= "<a href='#img-manager-anchor' class='dbd-button dbd-button--red dbd-form__delete-button delete-selected-image " . $sHideClass . "' data-input-id='" . $sFieldId . "' data-show-delete-confirm='" . ($this->showDeletePickedImageConfirm ? "true" : "false") . "'>$deleteBtnSVG</a>";
        $field      .= "<a href='#img-manager-anchor' class='dbd-button dbd-form__upload-button open-modal-imagemanager' data-aspect-ratio='" . $this->aspectRatio . "' data-crop-view-mode='" . $this->cropViewMode . "' data-input-id='" . $sFieldId . "'>";
        $field      .= $uploadBtnSVG;
        $field      .= "</a></div>";

        //show preview if is true
        if ($this->showPreview == true) {
            $sHideClass   = ($mImageManager == null) ? "hide" : "";
            $sImageSource =
                isset($mImageManager->id) ? \Yii::$app->imagemanager->getImagePath(
                    $mImageManager->id,
                    500,
                    500,
                    'inset'
                ) : "";

            $field .= '<div class="image-wrapper ' . $sHideClass . '">'
                      . '<img id="' . $sFieldId . '_image" alt="Thumbnail" class="img-responsive img-preview" src="' . $sImageSource . '">'
                      . '</div>';
        }

        //close image-manager-input div
//        $field .= "</div>";

        echo $field;

        $this->registerClientScript();
    }

    /**
     * Registers js Input
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        ImageManagerInputAsset::register($view);

        //set baseUrl from image manager
        $sBaseUrl = Url::to(['/imagemanager/manager']);
        //set base url
        $view->registerJs("imageManagerInput.baseUrl = '" . $sBaseUrl . "';");
        $view->registerJs(
            "imageManagerInput.message = " . Json::encode([
                'imageManager'         => Yii::t('imagemanager', 'Image manager'),
                'detachWarningMessage' => Yii::t('imagemanager', 'Are you sure you want to detach the image?'),
            ]) . ";"
        );
    }

}
