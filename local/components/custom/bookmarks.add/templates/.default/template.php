<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
?>
<div class="container">
    <? if ($arResult['ERRORS']): ?>
        <div class="alert alert-danger" role="alert">
            <?= $arResult['ERRORS'] ?>
        </div>
    <? endif ?>
    <form method="post">
        <div class="form-group">
            <input type="text" name="url" class="form-control" id="url" placeholder="<?= Loc::getMessage('URL')?>">
        </div>
        <div class="form-group">
            <input type="password" name="delete_code" class="form-control" placeholder="<?= Loc::getMessage('DELETE_ROW')?>">
        </div>
        <button type="submit" class="btn btn-dark"><?= Loc::getMessage('ADD')?></button>
    </form>
</div>