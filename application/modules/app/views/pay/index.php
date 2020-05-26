<?php
alert_error($error);
?>

<?php if (($item ?? null)) :?>
    <div class="card px-2">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div class="col-lg-3 pl-0">
                            <p class="mb-0 mt-5">Дата : <?=date(DATE_FORMAT_FULL);?></p>
                        </div>
                    </div>
                    <div class="mt-5 d-flex justify-content-center w-100">
                        <div class="table-responsive w-100">
                            <table class="table">
                                <thead>
                                    <tr class="bg-dark text-white">
                                            <th>#</th>
                                            <th>Описание</th>
                                            <th class="text-right">Кол-во</th>
                                            <th class="text-right">Сумма</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;?>
                                    <?php foreach ($item['list'] as $val) :?>
                                        <tr class="text-right">
                                            <td class="text-left"><?=$i?></td>
                                            <td class="text-left"><?=$val['description']?></td>
                                            <td>1</td>
                                            <td>
                                                <?php if ($val['price'] !== $val['origin_price']) :?>
                                                    <div><s><?=number_format($val['origin_price'], 2, '.', '')?>&nbsp;<?=PRICE_CHAR?></s></div>
                                                    <?=number_format($val['price'], 2, '.', '')?>&nbsp;<?=PRICE_CHAR?>
                                                <?php else :?>
                                                    <?=number_format($val['price'], 2, '.', '')?>&nbsp;<?=PRICE_CHAR?>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <?php $i++;?>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row pt-4">
                
                <div class="col-12 col-md-6 col-lg-4">
                    <?php if ($is_new) :?>
                        <?php if (empty($promocode)) :?>
                            <form action="" method="post">
                                <div class="input-group">
                                    <input type="text" name="promocode" placeholder="Промокод" value="<?=set_value('promocode', '')?>" class="form-control">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success float-right">Применить</button>
                                    </div>
                                </div>
                            </form>
                        <?php else :?>
                            <form action="" method="post">
                                <div class="input-group">
                                    <input type="hidden" name="del" value="1">
                                    <input type="hidden" name="promocode" value="<?=$promocode['code']?>">
                                    <button type="button" class="btn btn-success"><?=htmlspecialchars($promocode['code'])?></button>
                                    <button type="submit" class="btn btn-danger"><i class="mdi mdi-close"></i></button>
                                </div>
                            </form>
                        <?php endif;?>
                    <?php endif;?>
                </div>
               
                <div class="col-12 col-md-6 col-lg-8">
                    <?php if ($item['price'] !== $item['origin_price']) :?>
                        <h4 class="text-right mb-5">
                            Всего : <s class="text-muted"><?=number_format($item['origin_price'], 2, '.', '')?>&nbsp;<?=PRICE_CHAR?></s><br>
                            <?=number_format($item['price'], 2, '.', '')?>&nbsp;<?=PRICE_CHAR?>
                        </h4>
                    <?php else :?>
                        <h4 class="text-right mb-5">Всего : <?=number_format($item['price'], 2, '.', '')?>&nbsp;<?=PRICE_CHAR?></h4>
                    <?php endif;?>
                </div>

                <div class="col-12">
                    <?php if ($is_new && empty($promocode) === false && $item['params']['subscr_type'] === 1) :?>
                        <div class="alert alert-danger">Внимание! При помесячной оплате, скидка действует только на первый месяц.</div>
                    <?php endif;?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <form action="" method="post">
                        <input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
                        <button type="submit" class="btn btn-success float-right mt-4"><i class="mdi mdi-telegram mr-1"></i>Оплата</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>

<script src="<?=TEMPLATE_DIR?>/main_v1/vendors/sweetalert/sweetalert.min.js"></script>