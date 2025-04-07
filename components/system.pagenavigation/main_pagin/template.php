<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    exit;
}

?>

<section class="section pagination" js-nav-box>
    <div class="pagination__body">
        <div class="pagination__controls">

            <!-- Стрелка назад -->
            <?php if ($arResult['NavPageNomer'] !== 1): ?>
                <a class="pagination__navigation pagination__navigation_prev" href="<?= $arResult['reduct']["other_page"]["link_back"] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12.4381 3.62412L21.0001 12.1861L12.4381 20.748" stroke="#262B2C" stroke-width="2" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="bevel"></path>
                        <path d="M3.00018 12.1865L20.1396 12.1865" stroke="#262B2C" stroke-width="2" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="round"></path>
                    </svg>
                </a>
            <?php endif; ?>

            <!-- Кнопки страниц -->
            <?php while ($arResult["nStartPage"] <= $arResult["nEndPage"]): ?>
                <?php if ($arResult["nStartPage"] == $arResult["NavPageNomer"]): ?>
                    <li class="pagination__item" style="list-style-type: none;">
                        <a class="pagination__item-link active" href="#"><?= $arResult["nStartPage"] ?></a>
                    </li>
                <?php else: ?>
                    <li class="pagination__item" style="list-style-type: none;">
                        <a class="pagination__item-link" href="<?= $arResult['reduct']["pages"]["link"] ?>page=<?= $arResult["nStartPage"] ?>"><?= $arResult["nStartPage"] ?> ==</a>
                    </li>
                <?php endif; ?>
                <?php $arResult["nStartPage"]++ ?>
            <?php endwhile; ?>

            <!-- Стрелка вперёд -->
            <?php if ($arResult['NavPageNomer'] != $arResult['NavPageCount']): ?>
                <a class="pagination__navigation pagination__navigation_next" href="<?= $arResult['reduct']["other_page"]["link_next"] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12.4381 3.62412L21.0001 12.1861L12.4381 20.748" stroke="#262B2C" stroke-width="2" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="bevel"></path>
                        <path d="M3.00018 12.1865L20.1396 12.1865" stroke="#262B2C" stroke-width="2" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="round"></path>
                    </svg>
                </a>
            <?php endif; ?>
        </div>

        <!-- Пагинация -->
        <?php if ($arResult['NavPageNomer'] != $arResult['NavPageCount']): ?>
            <button class="button button_light !w-full justify-center" id="load-more" js-show-more="<?= $arResult['reduct']['show_more']['link'] ?>">
                <span class="button__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M20 9L12 17L4 9" stroke="#262B2C" stroke-width="2" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="bevel"></path>
                    </svg>
                </span>
                <span class="button__text">показать еще</span>
            </button>
        <?php endif; ?>
    </div>
</section>