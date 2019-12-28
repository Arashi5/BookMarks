<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application,
    Bitrix\Main\Localization\Loc;

$request = Application::getInstance()->getContext()->getRequest();

?>

<div class="container">
    <div class="row">
        <div class="col col-sm-3">
         <a href="?ADD" class="btn btn-dark" role="button" aria-pressed="true"><?= Loc::getMessage('ADD_LINK')?></a>
        </div>
        <?if ($arResult['EXCEL']):?>
        <div class="col">
            <a href="<?= $arResult['EXCEL']?>" download class="btn btn-success" role="button" aria-pressed="true"><?= Loc::getMessage('EXCEL')?></a>
        </div>
        <?endif?>
    </div>
<?if ($arResult['ITEMS']):?>
    <br>
    <?$i= 0?>
    <?foreach ($arResult['SORT_LIST'] as $name => $propName):?>
    <label><?= $name?>: </label>
    <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-sm btn-secondary order
        <?= ($arParams['SORT']['BY'] === $propName
            && $arParams['SORT']['ORDER'] === 'desc')
            ? 'active': ''?>"
               data-order="desc" data-by="<?= $propName?>">
            <input style="display:none;" type="radio" id="option<?= ++$i?>" >
            <i class="fa fa-arrow-down" aria-hidden="true"></i>
        </label>
        <label class="btn btn-sm btn-secondary order
            <?= ($arParams['SORT']['BY'] === $propName
            && $arParams['SORT']['ORDER'] === 'asc')
            ? 'active'
            : ''?>"
               data-order="asc" data-by="<?= $propName?>">
            <input style="display:none;" type="radio" id="option<?= ++$i?>">
            <i class="fa fa-arrow-up" aria-hidden="true"></i>
        </label>
    </div>
    <?endforeach;?>
    <div class="row">
      <table class="table table-hover table-inverse">
        <thead>
        <tr>
            <th><?= Loc::getMessage('#')?></th>
            <th><?= Loc::getMessage('DATE')?></th>
            <th><?= Loc::getMessage('FAVICON')?></th>
            <th><?= Loc::getMessage('URL')?></th>
            <th><?= Loc::getMessage('TITLE')?></th>
            <th><?= Loc::getMessage('DETAIL')?></th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($arResult['ITEMS'] as $key => $arItem):?>
        <tr>
            <th scope="row"><?= ++$key?></th>
            <td><?= $arItem['UF_CREATED_AT']?></td>
            <td> <img class="img-thumbnail" alt="<?= $arItem['UF_TITLE']?>" src="<?= CFile::GetPath($arItem['UF_FAVICO'])?>"/></td>
            <td><a class="text-dark" href="<?= $arItem['UF_URL']?>"><?= $arItem['UF_URL']?></a></td>
            <td><?= $arItem['UF_TITLE']?></td>
            <td><a href="?ID=<?= $arItem['ID']?>"><?= Loc::getMessage('MORE')?></a></td>
        </tr>
        <?endforeach;?>
        </tbody>
    </table>
    </div>
    <?if ($arResult['NAVIGATION']):?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?foreach ($arResult['NAVIGATION'] as $key => $page):?>
            <li class="page-item  <?= ($request->get('page') == $page || (!$request->get('page') && !$key))?'active':''?>">
                <a class="page-link" href="?page=<?=$page?>"><?= $page?></a>
            </li>
            <?endforeach?>
        </ul>
    </nav>
    <?endif?>
<?endif?>
</div>
<script>
    $(function () {
        let sort;
        let list;

        list = document.getElementsByClassName('append-data-list');
        sort = document.querySelectorAll(".order");

        sort.forEach(function (item) {
            item.addEventListener('click', function (event) {
                var by = this.getAttribute('data-by');
                var order = this.getAttribute('data-order');

                BX.ajax({
                    url: location.href,
                    data: {
                        'order': order,
                        'by': by,
                        'page': 1,
                        'reload': true
                    },
                    method: 'POST',
                    dataType: 'html',
                    timeout: 30,
                    async: true,
                    processData: true,
                    start: true,
                    cache: false,
                    onsuccess: function (data) {
                        list[0].innerHTML = data;
                    },
                });

            });
        })
    });
</script>