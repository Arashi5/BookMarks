<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
?>
<?if ($arResult):?>
<div class="container">
    <div class="row">
        <a href="<?= $_SERVER['SCRIPT_URI']?>" role="button" aria-pressed="true"><?= Loc::getMessage('BACK')?></a>
    </div>
    <br>
    <div class="row">
        <div class="col-4">
            <img src="<?= $arResult['UF_FAVICO']?>" alt="..." class="img-thumbnail">
        </div>
        <div class="col-md-8">
            <div class="list-group">
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= Loc::getMessage('TITLE')?></h5>
                    </div>
                    <p class="mb-1"><?= $arResult['UF_TITLE']?></p>
                </div>
                <div  class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= Loc::getMessage('DATE_CREATE')?></h5>
                    </div>
                    <p class="mb-1"><?= ($arResult['UF_CREATED_AT'])->toString()?></p>
                </div>
                <div  class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= Loc::getMessage('DATE_CREATE')?></h5>
                    </div>
                    <p class="mb-1"><?= $arResult['UF_URL']?></p>
                </div>
                <div  class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= Loc::getMessage('DESCRIPTION')?></h5>
                    </div>
                    <p class="mb-1"><?= $arResult['UF_META_DESC']?></p>
                </div>
                <div  class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= Loc::getMessage('KEYS')?></h5>
                    </div>
                    <p class="mb-1"><?= $arResult['UF_META_KEY']?></p>
                </div>
                <?if (!$arResult['UF_DELETE_KEY']):?>
                <div  class="list-group-item">
                    <p class="append-data p-3 mb-2 text-danger"></p>
                    <form action="javascript:void(0)" method="post">
                        <div class="form-group">
                            <label for="delete"><?= Loc::getMessage('DELETE_LINK')?></label>
                            <input type="text" class="form-control delete-code" aria-describedby="deleteNotice" id="delete" placeholder="<?= Loc::getMessage('CODE')?>">
                            <small id="deleteNotice" class="form-text text-muted"><?= Loc::getMessage('4_LIFE')?></small>
                        </div>
                        <button type="button" class="btn btn-danger delete-button"><?= Loc::getMessage('DELETE')?></button>
                    </form>
                </div>
                <?endif?>
            </div>
        </div>
    </div>
</div>
<?else:?>
    <p class="append-data p-3 mb-2 text-danger"><?= Loc::getMessage('NOT_FOUND')?></p>
<?endif?>
<script>

    let deleteRow = {};
    let code = {};
    let exception = {};

    deleteRow = document.getElementsByClassName('delete-button');
    deleteRow[0].addEventListener("click", function () {

        code = document.getElementsByClassName('delete-code');
        exception = document.getElementsByClassName('append-data');
        if (code[0].value) {

            let request = BX.ajax.runComponentAction(
                'custom:ajax',
                'deleteElement',
                {
                    mode: 'class',
                    data: {
                        code: code[0].value,
                        hlbId: <?= $arParams['HL_ID']?>,
                        elementId: <?= $arParams['ELEMENT_ID']?>,
                    }
                });

            request.then(function (response) {
               if (response.data.result === true) {
                  var redirect = confirm('<?= Loc::getMessage('SUCCESS')?>');
                  if (redirect === true) {
                      window.location.href = '<?= $_SERVER['SCRIPT_URI']?>';
                      exception[0].empty();
                  }

               } else {
                   exception[0].innerHTML = '<?= Loc::getMessage('DENIED')?>';
               }
            });
        } else {
            exception[0].innerHTML = '<?= Loc::getMessage('INSERT_CODE')?>';
        }
    });
</script>
