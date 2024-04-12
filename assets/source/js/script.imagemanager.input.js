var imageManagerInput = {
  baseUrl: null,
  //language
  message: null,
  dialog: null,
  dialogOverlay: null,
  //init imageManagerInput
  init: function () {
    //create modal
    imageManagerInput.initModal()
  },
  //create image Manager modal
  initModal: function () {
    //check if modal not jet exists
    if ($('#modal-imagemanager').length === 0) {
      //set html modal in var
      let sModalHtml = '<dialog class="dialog dbd-dialog" id="modal-imagemanager" data-name="image-manager" data-modal="true" data-clickable-backdrop="true">'
      sModalHtml += '<iframe class="dialog__inner-wrapper123"></iframe>'
      sModalHtml += '</dialog>'
      //prepend data to body
      $('body').prepend(sModalHtml)

      imageManagerInput.dialog = document.getElementById('modal-imagemanager')
      imageManagerInput.dialogOverlay = document.querySelector('.dialog-overlay')
      if (!imageManagerInput.dialogOverlay) {
        const overlay = document.createElement('div')
        overlay.classList.add('dialog-overlay', 'overlay')
        document.body.appendChild(overlay)
        imageManagerInput.dialogOverlay = overlay
      }
    }
  },
  //open media manager modal
  openModal: function (inputId, aspectRatio, cropViewMode) {
    //get selected item
    var iImageId = $('#' + inputId).val()
    var srcImageIdQueryString = ''
    if (iImageId !== '') {
      srcImageIdQueryString = '&image-id=' + iImageId
    }
    //create iframe url
    var queryStringStartCharacter = ((imageManagerInput.baseUrl).indexOf('?') == -1) ? '?' : '&'
    var imageManagerUrl = imageManagerInput.baseUrl + queryStringStartCharacter + 'view-mode=iframe&input-id=' + inputId + '&aspect-ratio=' + aspectRatio + '&crop-view-mode=' + cropViewMode + srcImageIdQueryString
    //set iframe path
    $('#modal-imagemanager iframe').attr('src', imageManagerUrl)
    //set translation title for modal header
    // $('#modal-imagemanager .modal-dialog .modal-header h4').text(imageManagerInput.message.imageManager)
    //open modal


    imageManagerInput.dialog.showModal()
    imageManagerInput.dialogOverlay.classList.add('is-visible')
    imageManagerInput.dialog.focus()

    imageManagerInput.dialog.addEventListener('click', (e) => {
      e.target === imageManagerInput.dialog && imageManagerInput.closeModal();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && imageManagerInput.dialog) {
        imageManagerInput.closeModal();
      }
    });

  },
  //close media manager modal
  closeModal: function () {
    imageManagerInput.dialog.close();
    imageManagerInput.dialog.classList.add('close');
    imageManagerInput.dialogOverlay.classList.remove('is-visible');
  },
  //delete picked image
  deletePickedImage: function (inputId) {
    //remove value of the input field
    const sFieldId = inputId
    const sFieldNameId = sFieldId + '_name'
    const sImagePreviewId = sFieldId + '_image'
    const bShowConfirm = JSON.parse($('.delete-selected-image[data-input-id=\'' + inputId + '\']').data('show-delete-confirm'))
    //show warning if bShowConfirm == true
    if (bShowConfirm) {
      if (confirm(imageManagerInput.message.detachWarningMessage) == false) {
        return false
      }
    }

    //set input data
    $('#' + sFieldId).val('')
    $('#' + sFieldNameId).val('')
    //trigger change
    $('#' + sFieldId).trigger('change')
    //hide image
    $('#' + sImagePreviewId).attr('src', '').parent().addClass('hide')
    //delete hide class
    $('.delete-selected-image[data-input-id=\'' + inputId + '\']').addClass('hide')
  }
}

$(document).ready(function () {
  //init Image manage
  imageManagerInput.init()

  //open media manager modal
  $(document).on('click', '.open-modal-imagemanager', function () {
    const aspectRatio = $(this).data('aspect-ratio')
    const cropViewMode = $(this).data('crop-view-mode')
    const inputId = $(this).data('input-id')
    //open selector id
    imageManagerInput.openModal(inputId, aspectRatio, cropViewMode)
  })

  // $(document).on('click', '.dbd-form__upload-label', function () {
  //   const aspectRatio = $(this).data('aspect-ratio')
  //   const cropViewMode = $(this).data('crop-view-mode')
  //   const inputId = $(this).data('input-id')
  //   //open selector id
  //   imageManagerInput.openModal(inputId, aspectRatio, cropViewMode)
  // })

  //delete picked image
  $(document).on('click', '.delete-selected-image', function () {

    const inputId = $(this).data('input-id')
    //open selector id
    imageManagerInput.deletePickedImage(inputId)
    //reload pjax container
    $.pjax.reload('#pjax-mediamanager', { push: false, replace: false, timeout: 5000, scrollTo: false })
  })
})