const imageManagerModule = {
  //params for input selector
  fieldId: null,
  cropRatio: null,
  cropViewMode: 1,
  defaultImageId: null,
  selectType: null,
  //current selected image
  selectedImage: null,
  //language
  message: null,
  //init imageManager
  init: function () {
    //init cropper
    $('#module-imagemanager .image-cropper .image-wrapper img#image-cropper').cropper({
      viewMode: imageManagerModule.cropViewMode
    })

    //preselect image if image-id isset
    if (imageManagerModule.defaultImageId !== '') {
      imageManagerModule.selectImage(imageManagerModule.defaultImageId)
    }

    // //set selected after pjax complete
    // $('#pjax-mediamanager').on('pjax:complete', function() {
    // 	if(imageManagerModule.selectedImage !== null){
    // 		imageManagerModule.selectImage(imageManagerModule.selectedImage.id);
    // 	}
    // });
  },
  //filter result
  filterImageResult: function (searchTerm) {
    const newUrl = window.queryStringParameter.set(window.location.href, 'ImageManagerSearch[globalSearch]', searchTerm)

    $.pjax({
      url: newUrl,
      container: '#pjax-mediamanager',
      push: false,
      replace: false,
      timeout: 5000,
      scrollTo: false
    })
    $.pjax.reload({ url: newUrl, container: '#pjax-mediamanager' }, {
      push: false,
      replace: false,
      timeout: 5000,
      scrollTo: false
    })
  },
  //select an image
  selectImage: function (id) {
    //set selected class
    $('#module-imagemanager .image-manager__images .item').removeClass('is-active')
    $('#module-imagemanager .image-manager__images .item[data-key=\'' + id + '\']').addClass('is-active')
    //get details
    imageManagerModule.getDetails(id)
  },
  //pick the selected image
  pickImage: function () {
    //switch between select type
    switch (imageManagerModule.selectType) {
      //default widget selector
      case 'input':
        //get id data
        const sFieldId = imageManagerModule.fieldId
        const sFieldNameId = sFieldId + '_name'
        const sFieldImageId = sFieldId + '_image'
        //set input data
        $('#' + sFieldId, window.parent.document).val(imageManagerModule.selectedImage.id)
        $('#' + sFieldNameId, window.parent.document).val(imageManagerModule.selectedImage.fileName)
        $('#' + sFieldImageId, window.parent.document).attr('src', imageManagerModule.selectedImage.image).parent().removeClass('hide')
        //trigger change
        parent.$('#' + sFieldId).trigger('change')
        //show delete button
        $('.delete-selected-image[data-input-id=\'' + sFieldId + '\']', window.parent.document).removeClass('hide')
        //close the modal
        window.parent.imageManagerInput.closeModal()
        break
      //CKEditor selector
      case 'ckeditor':
      //TinyMCE Selector
      case 'tinymce':
        //check if isset image
        if (imageManagerModule.selectedImage !== null) {
          //call action by ajax
          $.ajax({
            url: imageManagerModule.baseUrl + '/get-original-image',
            type: 'POST',
            data: {
              ImageManager_id: imageManagerModule.selectedImage.id,
              _csrf: $('meta[name=csrf-token]').prop('content')
            },
            dataType: 'json',
            success: function (responseData, textStatus, jqXHR) {
              let sField
//set attributes for each selector
              if (imageManagerModule.selectType == 'ckeditor') {
                sField = window.queryStringParameter.get(window.location.href, 'CKEditorFuncNum')
                window.top.opener.CKEDITOR.tools.callFunction(sField, responseData)
                window.self.close()
              } else if (imageManagerModule.selectType == 'tinymce') {
                sField = window.queryStringParameter.get(window.location.href, 'tag_name')
                window.opener.document.getElementById(sField).value = responseData
                window.close()
                window.opener.focus()
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              alert('Error: can\'t get item')
            }
          })
        } else {
          alert('Error: image can\'t picked')
        }
        break
    }

  },
  //delete image
  deleteImageById: function (id, event) {
    event.stopPropagation()
    if (confirm(imageManagerModule.message.deleteMessage)) {
      imageManagerModule.editor.close()
      if (id !== null) {
        //call action by ajax
        $.ajax({
          url: imageManagerModule.baseUrl + '/delete',
          type: 'POST',
          data: {
            ImageManager_id: id,
            _csrf: $('meta[name=csrf-token]').prop('content')
          },
          dataType: 'json',
          success: function (responseData, textStatus, jqXHR) {
            //check if delete is true
            if (responseData.delete === true) {
              //delete item element
              $('#module-imagemanager .image-manager__images .item[data-key=\'' + id + '\']').remove()
              $('#module-imagemanager .image-info').addClass('hide')
              //set selectedImage to null
              imageManagerModule.selectedImage = null
              //close edit
              $.pjax.reload('#pjax-mediamanager', { push: false, replace: false, timeout: 5000, scrollTo: false })
            } else {
              alert('Error: item is not deleted')
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error: can\'t delete item')
          }
        })
      } else {
        alert('Error: image can\'t delete, no image isset set')
      }
    }

  },
  //delete the selected image
  deleteSelectedImage: function () {
    //confirm message
    if (confirm(imageManagerModule.message.deleteMessage)) {
      //close editor
      imageManagerModule.editor.close()
      //check if isset image
      if (imageManagerModule.selectedImage !== null) {
        //call action by ajax
        $.ajax({
          url: imageManagerModule.baseUrl + '/delete',
          type: 'POST',
          data: {
            ImageManager_id: imageManagerModule.selectedImage.id,
            _csrf: $('meta[name=csrf-token]').prop('content')
          },
          dataType: 'json',
          success: function (responseData, textStatus, jqXHR) {
            //check if delete is true
            if (responseData.delete === true) {
              //delete item element
              $('#module-imagemanager .image-manager__images .item[data-key=\'' + imageManagerModule.selectedImage.id + '\']').remove()
              //add hide class to info block
              $('#module-imagemanager .image-info').addClass('hide')
              //set selectedImage to null
              imageManagerModule.selectedImage = null
              //close edit
              $.pjax.reload('#pjax-mediamanager', { push: false, replace: false, timeout: 5000, scrollTo: false })
            } else {
              alert('Error: item is not deleted')
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error: can\'t delete item')
          }
        })
      } else {
        alert('Error: image can\'t delete, no image isset set')
      }
    }
  },
  //get image details
  getDetails: function (id, pickAfterGetDetails) {
    //set properties if not set
    pickAfterGetDetails = pickAfterGetDetails !== undefined ? pickAfterGetDetails : false

    if (isNaN(id)) {
      return
    }
    //call action by ajax
    $.ajax({
      url: imageManagerModule.baseUrl + '/view',
      type: 'POST',
      data: {
        ImageManager_id: id,
        _csrf: $('meta[name=csrf-token]').prop('content')
      },
      dataType: 'json',
      success: function (responseData, textStatus, jqXHR) {
        //set imageManagerModule.selectedImage property
        imageManagerModule.selectedImage = responseData

        //if need to pick image?
        if (pickAfterGetDetails) {
          imageManagerModule.pickImage()
          //else set data
        } else {
          //set text elements
          $('#module-imagemanager .image-manager__controls .img-manager-selected__name').text(responseData.fileName).attr('title', responseData.fileName)
          $('#module-imagemanager .image-manager__controls .img-manager-selected__info.created').text(responseData.created)
          $('#module-imagemanager .image-manager__controls .img-manager-selected__info.fileSize').text(responseData.fileSize)
          $('#module-imagemanager .image-manager__controls .img-manager-selected__info.dimensions .dimension-width').text(responseData.dimensionWidth)
          $('#module-imagemanager .image-manager__controls .img-manager-selected__info.dimensions .dimension-separator').text('×')
          $('#module-imagemanager .image-manager__controls .img-manager-selected__info.dimensions .dimension-height').text(responseData.dimensionHeight)
          $('#module-imagemanager .image-manager__controls .img-manager__image-wrapper').html('<img src=\'' + responseData.image + '\' alt=\'' + responseData.fileName + '\'/>')
          //remove hide class
          $('#module-imagemanager .image-manager__controls .img-manager-selected__wrapper .img-manager-select__name').removeClass('hide')
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Can\'t view image. Error: ' + jqXHR.responseText)
      }
    })
  },
  //upload file
  uploadSuccess: function (uploadResponse) {
    //close editor
    imageManagerModule.editor.close()

    //reload pjax container
    $.pjax.reload('#pjax-mediamanager', { push: false, replace: false, timeout: 5000, scrollTo: false })
  },
  //editor functions
  editor: {
    //open editor block
    open: function () {
      //show editer / hide overview
      $('#module-imagemanager .image-manager__cropper').show()
      $('#module-imagemanager .image-manager__images').hide()
    },
    //close editor block
    close: function () {
      //show overview / hide editer
      $('#module-imagemanager .image-manager__images').show()
      $('#module-imagemanager .image-manager__cropper').hide()
    },
    //open cropper
    openCropper: function () {
      //check if isset image
      if (imageManagerModule.selectedImage !== null) {
        //call action by ajax
        $.ajax({
          url: imageManagerModule.baseUrl + '/get-original-image',
          type: 'POST',
          data: {
            ImageManager_id: imageManagerModule.selectedImage.id,
            _csrf: $('meta[name=csrf-token]').prop('content')
          },
          dataType: 'json',
          success: function (responseData, textStatus, jqXHR) {
            //hide cropper
            $('#module-imagemanager .image-manager__cropper').css('visibility', 'hidden')
            //set image in cropper
            $('#module-imagemanager .image-manager__cropper .image-cropper .image-wrapper img#image-cropper').one('built.cropper', function () {
              //show cropper
              $('#module-imagemanager .image-manager__cropper').css('visibility', 'visible')
            })
              .cropper('reset')
              .cropper('setAspectRatio', parseFloat(imageManagerModule.cropRatio))
              .cropper('replace', responseData)
            //open editor
            imageManagerModule.editor.open()
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error: can\'t get item')
          }
        })
      } else {
        alert('Error: image can\'t crop, no image isset set')
      }
    },
    //apply crop
    applyCrop: function (pickAfterCrop) {
      //set propertie if not set
      pickAfterCrop = pickAfterCrop !== undefined ? pickAfterCrop : false
      //check if isset image
      if (imageManagerModule.selectedImage !== null) {
        //set image in cropper
        var oCropData = $('#module-imagemanager .image-manager__cropper .image-cropper .image-wrapper img#image-cropper').cropper('getData')
        //call action by ajax
        $.ajax({
          url: imageManagerModule.baseUrl + '/crop',
          type: 'POST',
          data: {
            ImageManager_id: imageManagerModule.selectedImage.id,
            CropData: oCropData,
            _csrf: $('meta[name=csrf-token]').prop('content')
          },
          dataType: 'json',
          success: function (responseData, textStatus, jqXHR) {
            //set cropped image
            if (responseData !== null) {
              //if pickAfterCrop is true? select directly else
              if (pickAfterCrop) {
                imageManagerModule.getDetails(responseData, true)
                //else select the image only
              } else {
                //set new image
                imageManagerModule.selectImage(responseData)
                //reload pjax container
                $.pjax.reload('#pjax-mediamanager', { push: false, replace: false, timeout: 5000, scrollTo: false })
              }
            }
            //close editor
            imageManagerModule.editor.close()
          },
          error: function (jqXHR, textStatus, errorThrown) {
            alert('Error: item is not cropped')
          }
        })
      } else {
        alert('Error: image can\'t crop, no image isset set')
      }
    }
  }
}

$(document).ready(function () {

  var delayTimer = null

  //init Image manage
  imageManagerModule.init()
  //on click select item (open view)
  $(document).on('click', '#module-imagemanager .image-manager__images .item', function () {
    //get id
    const ImageManager_id = $(this).data('key')
    //select image
    imageManagerModule.selectImage(ImageManager_id)
  })
  //on click pick image
  $(document).on('click', '#module-imagemanager .image-manager__controls-wrapper .pick-image-item', function () {
    imageManagerModule.pickImage()
    return false
  })
  //on click delete call "delete"
  $(document).on('click', '#module-imagemanager .image-manager__controls-wrapper .delete-image-item', function () {
    imageManagerModule.deleteSelectedImage()
    return false
  })
  //on click crop call "crop"
  $(document).on('click', '#module-imagemanager .image-manager__controls-wrapper .crop-image-item', function () {
    imageManagerModule.editor.openCropper()
    return false
  })
  //on click apply crop
  $(document).on('click', '#module-imagemanager .image-cropper .apply-crop', function () {
    imageManagerModule.editor.applyCrop()
    return false
  })
  //on click apply crop
  $(document).on('click', '#module-imagemanager .image-cropper .apply-crop-select', function () {
    imageManagerModule.editor.applyCrop(true)
    return false
  })
  //on click cancel crop
  $(document).on('click', '#module-imagemanager .image-cropper .cancel-crop', function () {
    imageManagerModule.editor.close()
    return false
  })
  //on keyup change set filter
  $(document).on('keyup change', '#input-mediamanager-search', function () {
    //clear timer
    if (delayTimer) clearTimeout(delayTimer)
    //set delay timer
    const value = $(this).val()
    delayTimer = setTimeout(function () {
      imageManagerModule.filterImageResult(value)
    }, 200)
  })

})

/*
 * return new get param to url
 */
window.queryStringParameter = {
  get: function (uri, key) {
    var reParam = new RegExp('(?:[\?&]|&amp;)' + key + '=([^&]+)', 'i')
    var match = uri.match(reParam)
    return (match && match.length > 1) ? match[1] : null
  },
  set: function (uri, key, value) {
    //replace brackets
    var keyReplace = key.replace('[]', '').replace(/\[/g, '%5B').replace(/\]/g, '%5D')
    //replace data
    var re = new RegExp('([?&])' + keyReplace + '=.*?(&|$)', 'i')
    var separator = uri.indexOf('?') !== -1 ? '&' : '?'
    if (uri.match(re)) {
      return uri.replace(re, '$1' + keyReplace + '=' + value + '$2')
    } else {
      return uri + separator + keyReplace + '=' + value
    }
  }
}

const dialogs = document.querySelectorAll('.dialog');
let dialogOverlay = document.querySelector('.dialog-overlay');
if (!dialogOverlay) {
  const overlay = document.createElement('div');
  overlay.classList.add('dialog-overlay', 'overlay');
  document.body.appendChild(overlay);
  dialogOverlay = overlay;
}

dialogs.forEach((dialog) => {
  const openButtons = document.querySelectorAll(
    `[data-dialog=${dialog.dataset.name}]`,
  );
  const closeButton = dialog.querySelectorAll(
    '.close-button, [data-close-dialog]',
  );

  function animationHandler() {
    dialog.classList.remove('close');
    dialog.close();
    dialog.removeEventListener('animationcancel', cancelAnimationHandler);
  }

  function cancelAnimationHandler() {
    dialog.classList.remove('close');
    dialog.removeEventListener('animationend', animationHandler);
  }

  const closeDialog = () => {
    dialog.classList.add('close');
    dialogOverlay.classList.remove('is-visible');

    // Reset listeners if the Escape button has been pressed
    dialog.addEventListener('animationcancel', cancelAnimationHandler);

    dialog.addEventListener('animationend', animationHandler, { once: true });
  };

  dialog.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      dialogOverlay.classList.remove('is-visible');
    }
  });

  openButtons.forEach((openButton) => {
    openButton.addEventListener('click', () => {
      dialogs.forEach((dialog) => {
        dialog.close();
      });
      if (dialog.dataset.modal === 'true') {
        dialog.showModal();
        dialogOverlay.classList.add('is-visible');
      } else {
        dialog.show();
      }
      // Reset initial dialog focus
      dialog.focus();
    });
  });

  closeButton.forEach((closeButton) => {
    closeButton.addEventListener('click', () => {
      closeDialog();
    });
  });

  if (dialog.dataset.clickableBackdrop === 'true') {
    dialog.addEventListener('click', (e) => {
      e.target === dialog && closeDialog();
    });
  }
});