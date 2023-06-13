<?php

namespace noam148\imagemanager\components;

use Yii;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use noam148\imagemanager\models\ImageManager;
use noam148\imagemanager\assets\ImageManagerInputAsset;

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
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@noam148/imagemanager/messages'
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
        $mImageManager = null;
        $sFieldId = null;
        //start input group
        $field = "<div class='image-manager-input'>";
        $field .= "<div class='row pl-0'>";
        //set input fields
        if ($this->hasModel()) {
            //get field id
            $sFieldId = Html::getInputId($this->model, $this->attribute);
            $sFieldNameId = $sFieldId . "_name";
            //get attribute name
            $sFieldAttributeName = Html::getAttributeName($this->attribute);
            //get filename from selected file
            $ImageManager_id = $this->model->{$sFieldAttributeName};
            $ImageManager_fileName = null;
            $mImageManager = ImageManager::findOne($ImageManager_id);
            if ($mImageManager !== null) {
                $ImageManager_fileName = $mImageManager->fileName;
            }
            //create field
            $field .= Html::textInput($this->attribute, $ImageManager_fileName, ['class' => 'custom-border-input', 'id' => $sFieldNameId, 'readonly' => true]);
            $field .= Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $field .= Html::textInput($this->name . "_name", null, ['readonly' => true]);
            $field .= Html::hiddenInput($this->name, $this->value, $this->options);
        }

        $deleteBtnSVG = <<<SVG
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.5304 1.53033C15.8232 1.23744 15.8232 0.762563 15.5304 0.46967C15.2375 0.176777 14.7626 0.176777 14.4697 0.46967L8.00001 6.93936L1.53033 0.469673C1.23744 0.17678 0.762563 0.17678 0.46967 0.469673C0.176777 0.762566 0.176777 1.23744 0.46967 1.53033L6.93935 8.00002L0.469697 14.4697C0.176804 14.7626 0.176804 15.2374 0.469697 15.5303C0.76259 15.8232 1.23746 15.8232 1.53036 15.5303L8.00001 9.06068L14.4697 15.5303C14.7626 15.8232 15.2374 15.8232 15.5303 15.5303C15.8232 15.2374 15.8232 14.7626 15.5303 14.4697L9.06067 8.00002L15.5304 1.53033Z" fill="#DF1609"></path>
            </svg>
        SVG;

        $uploadBtnSVG = <<<SVG
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17 9.00195C19.175 9.01395 20.353 9.11095 21.121 9.87895C22 10.758 22 12.172 22 15V16C22 18.829 22 20.243 21.121 21.122C20.243 22 18.828 22 16 22H8C5.172 22 3.757 22 2.879 21.122C2 20.242 2 18.829 2 16V15C2 12.172 2 10.758 2.879 9.87895C3.647 9.11095 4.825 9.01395 7 9.00195" stroke="#713EE6" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M12 15V2M12 2L15 5.5M12 2L9 5.5" stroke="#713EE6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        SVG;

        //end input group
        $sHideClass = $ImageManager_id === null ? 'hide' : '';
        $field .= "<a href='#ad-block' class='btn btn-red-light px-24 py-3 br-2 ml-3 h-100 delete-selected-image " . $sHideClass . "' data-input-id='" . $sFieldId . "' data-show-delete-confirm='" . ($this->showDeletePickedImageConfirm ? "true" : "false") . "'>$deleteBtnSVG</a>";
        $field .= "<a href='#ad-block' class='btn btn-general-light px-24 py-3 br-2 ml-3 mr-4 h-100 open-modal-imagemanager' data-aspect-ratio='" . $this->aspectRatio . "' data-crop-view-mode='" . $this->cropViewMode . "' data-input-id='" . $sFieldId . "'>";
        $field .= $uploadBtnSVG;
        $field .= "</a></div>";

        //show preview if is true
        if ($this->showPreview == true) {
            $sHideClass = ($mImageManager == null) ? "hide" : "";
            $sImageSource = isset($mImageManager->id) ? \Yii::$app->imagemanager->getImagePath($mImageManager->id, 500, 500, 'inset') : "";

            $field .= '<div class="image-wrapper ' . $sHideClass . '">'
                . '<img id="' . $sFieldId . '_image" alt="Thumbnail" class="img-responsive img-preview" src="' . $sImageSource . '">'
                . '</div>';
        }

        //close image-manager-input div
        $field .= "</div>";

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
        $view->registerJs("imageManagerInput.message = " . Json::encode([
                'imageManager' => Yii::t('imagemanager', 'Image manager'),
                'detachWarningMessage' => Yii::t('imagemanager', 'Are you sure you want to detach the image?'),
            ]) . ";");
    }

}
