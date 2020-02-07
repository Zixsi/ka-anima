<div class="row">
    <div class="col-6">
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title">Группа <?=$item['name']?> (<?=$item['code']?>)</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th>Ученик</th>
                            <th class="text-center" width="120">Задания</th>
                            <th class="text-center" width="120">Непроверено</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-secondary">
                            <td></td>
                            <td class="text-white">Всего</td>
                            <td class="text-center text-white"></td>
                            <td class="text-center text-white"><?=$total_not_verified_works?></td>
                        </tr>
                        
                        <?php $user_index = 1;?>
                        <?php if ($users) :?>
                            <?php $i = 0;?>
                            <?php foreach ($users as $val) :?>
                                <tr <?=($user && $val['id'] == $user['id'])?'class="bg-info"':''?>>
                                    <td><?=(++$i)?></td>
                                    <td>
                                        <a href="./?user=<?=$val['id']?>" class="text-primary"><?=$val['full_name']?></a>
                                    </td>
                                    <td class="text-center"><?=$val['reviews']?> / <?=$item['cnt']?></td>
                                    <td class="text-center"><?=($val['homeworks'] - $val['reviews'])?></td>
                                </tr>

                                <?php
                                if ($user && $val['id'] == $user['id']) {
                                    $user_index = $i;
                                }
                                ?>
                            <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <?php if ($user) :?>
                    <h3 class="card-title">
                        <?=$user_index?>. <a href="/profile/<?=$user['id']?>/" class="text-primary" target="_blank"><?=$user['full_name']?></a>
                    </h3>
                    <div>
                        <?php if ($homeworks) :?>
                            <div id="teacher--user-homeworks">
                                <?php //debug($homeworks);?>
                                <ul class="nav nav-tabs" role="tablist">
                                    <?php $n = 1;?>
                                    <?php foreach ($homeworks as $val) :?>
                                        <li class="nav-item">
                                            <a class="nav-link <?=($n === 1)?'active':''?>" id="tab-<?=$n?>" data-toggle="tab" href="#tab-content-<?=$n?>" role="tab" aria-controls="tab-content-<?=$n?>" aria-selected="true"><?=$n?></a>
                                            <span class="badge bg-<?=$val['status']?>"><?=count($val['homeworks'])?></span>
                                        </li>
                                        <?php $n++;?>
                                    <?php endforeach;?>
                                </ul>
                                <div class="tab-content">
                                    <?php $n = 1;?>
                                    <?php foreach ($homeworks as $val) :?>
                                        <div class="tab-pane fade <?=($n === 1)?'active show':''?>" id="tab-content-<?=$n?>" role="tabpanel">
                                            <h4 class="mb-4 mt-2"><?=$val['name']?></h4>
                                            <?php if (count($val['homeworks'])) :?>
                                                <div class="pb-2">
                                                    <?php if (empty($val['review'])) :?>
                                                        <button type="button" class="btn btn-primary btn-add-review" data-toggle="modal" data-group="<?=$item['id']?>" data-lecture="<?=$val['id']?>" data-user="<?=$user['id']?>">Добавить ревью</button>
                                                        <hr>
                                                    <?php else :?>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <a href="<?=$val['review']['video_url']?>" target="_blank" class="btn btn-primary btn-block">Видео</a>
                                                            </div>
                                                            <div class="col-6">
                                                                <?php if (!empty($val['review']['file_url'])) :?>
                                                                    <a href="<?=$val['review']['file_url']?>" target="_blank" class="btn btn-primary btn-block">Файл</a>
                                                                <?php endif;?>
                                                            </div>
                                                        </div>
                                                        <?php if (!empty($val['review']['text'])) :?>
                                                            <div class="row">
                                                                <div class="col-12 pt-2">
                                                                    <p><?=$val['review']['text']?></p>
                                                                </div>
                                                            </div>
                                                        <?php endif;?>
                                                        <div class="row">
                                                            <div class="col-12 text-right">
                                                                <button type="button" class="btn btn-outline-primary btn-xs btn-edit-review" data-toggle="modal" data-id="<?=$val['review']['id']?>">Редактировать ревью</button>
                                                                <button type="button" class="btn btn-outline-secondary btn-xs btn-remove-review" data-id="<?=$val['review']['id']?>">Удалить ревью</button>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    <?php endif;?>
                                                </div>
                                                <table class="table table-striped table-bordered">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th style="width: 150px;">Дата</th>
                                                            <th>Комментарий</th>
                                                            <th style="width: 90px;">#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($val['homeworks'] as $hw) :?>
                                                            <tr <?=($hw['is_new'])?'class="table-danger"':''?>>
                                                                <td><?=date(DATE_FORMAT_FULL, $hw['ts_timestamp'])?></td>
                                                                <td><?=$hw['comment']?></td>
                                                                <td class="text-right">
                                                                    <a href="./?user=<?=$user['id']?>&lecture=<?=$val['id']?>&action=download&target=homework" class="d-inline-block btn btn-primary btn-xs" target="_blank">Скачать</a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            <?php else :?>
                                                <div class="text-center">Нет загруженных файлов</div>
                                            <?php endif;?>
                                        </div>

                                        <?php $n++;?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                <?php else :?>
                    <h3 class="text-center">Выберите ученика</h3>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div class="col-6">
        <?=$this->wall->show($item['id'])?>
    </div>
</div>

<div class="modal fade" id="add-review-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ревью</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="group" value="0">
                    <input type="hidden" name="user" value="0">
                    <input type="hidden" name="lecture" value="0">
                    <div class="form-group">
                        <label>Ссылка на видео</label>
                        <input type="text" name="video_url" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ссылка на файл</label>
                        <input type="text" name="file_url" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Рекомендация</label>
                        <textarea name="text" class="form-control" style="height: 120px;"></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-review-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ревью</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="id" value="0">
                    <div class="form-group">
                        <label>Ссылка на видео</label>
                        <input type="text" name="video_url" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ссылка на файл</label>
                        <input type="text" name="file_url" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Рекомендация</label>
                        <textarea name="text" class="form-control" style="height: 120px;"></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>