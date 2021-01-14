<div class="row">
    <div class="col-6">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title pt-2">Группа <?= $item['name'] ?> (<?= $item['code'] ?>)</h3>
            </div>
            <div class="card-body">
                <div>
                    <h4>Преподаватель</h4>
                    <div class="row">
                        <div class="col-12 col-md-6 pt-2">
                            <?if(array_key_exists($item['teacher'], $teacherList)):?>
                            <a href="/admin/users/user/<?= $item['teacher'] ?>/" target="_blank"><?= $teacherList[$item['teacher']]['full_name'] ?></a>
                            <?else:?>
                            <span>--- нет ---</span>
                            <?endif;?>
                        </div>
                        <div class="col-12 col-md-6">
                            <?if(count($teacherList)):?>
                            <form action="" method="post" id="group-set-teacher">
                                <input type="hidden" name="group" value="<?= $item['id'] ?>">
                                <div class="input-group">
                                    <select name="teacher" class="form-control">
                                        <?foreach($teacherList as $row):?>
                                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
                                        <?endforeach;?>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Назначить</button>
                                    </div>
                                </div>
                            </form>
                            <?endif;?>
                        </div>
                    </div>
                    <hr>
                </div>
                <div>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary" id="group--add-user-btn">Добавить ученика</button>
                    </div>
                    <h4 class="pt-2">Ученики</h4>
                    <div class="clearfix"></div>
                </div>
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Ученик</th>
                            <th class="text-center" width="120">Задания</th>
                            <th class="text-center" width="120">Непроверено</th>
                            <th class="text-right" width="100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users):?>
                            <?php foreach($users as $val):?>
                            <?php $end = ($val['ts_end_timestamp'] < time());?>
                            <tr <?= ($user && $val['id'] == $user['id']) ? 'class="bg-info"' : '' ?>>
                                <td>
                                    <?php if(((int) $val['ts_end_timestamp'] < time() && (int) $val['ts_end_timestamp'] < (int) $item['timestamp_end'])):?>
                                    <span class="badge bg-danger text-white" 
                                          data-toggle="tooltip" 
                                          data-placement="bottom" 
                                          data-original-title="не продлена подписка" style="margin: 0px 5px 5px 0px;">!</span>
                                    <?php endif;?>
                                    <a href="./?user=<?= $val['id'] ?>" style="display: inline-block;"><?= $val['full_name'] ?></a>
                                </td>
                                <td class="text-center"><?= $val['reviews'] ?> / <?= ($item['cnt'] ?? 0) ?></td>
                                <td class="text-center"><?= ($val['homeworks'] - $val['reviews']) ?></td>
                                <td class="text-right">
                                    <form action="" method="post" class="group-remove-user" style="display: inline;">
                                        <input type="hidden" name="group" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="user" value="<?= $val['id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Удалить ученика из группы">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-primary btn-sm group--user-move-btn" data-id="<?=$val['id']?>" title="Перевод в группу">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                        <tr>
                            <td colspan="4" class="text-center">Ученики отсутствуют</td>
                        </tr>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
        <?if($users):?>
        <div class="card">
            <?if($user):?>
            <div class="card-header">
                <h3 class="card-title pt-2">
                    <a href="/admin/users/user/<?= $user['id'] ?>/" class="text-primary" target="_blank"><?= $user['full_name'] ?></a>
                </h3>
            </div>
            <?endif;?>
            <div class="card-body">
                <?if($user):?>
                <?if($homeworks):?>
                <div id="teacher--user-homeworks">
                    <?//debug($homeworks);?>
                    <ul class="nav nav-tabs" role="tablist">
                        <?$n = 1;?>
                        <?foreach($homeworks as $val):?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($n === 1) ? 'active' : '' ?>" id="tab-<?= $n ?>" data-toggle="tab" href="#tab-content-<?= $n ?>" role="tab" aria-controls="tab-content-<?= $n ?>" aria-selected="true"><?= $n ?></a>
                            <span class="badge bg-<?= $val['status'] ?>"><?= count($val['homeworks']) ?></span>
                        </li>
                        <?$n++;?>
                        <?endforeach;?>
                    </ul>
                    <div class="tab-content">
                        <?$n = 1;?>
                        <?foreach($homeworks as $val):?>
                        <div class="tab-pane fade <?= ($n === 1) ? 'active show' : '' ?>" id="tab-content-<?= $n ?>" role="tabpanel">
                            <?if(count($val['homeworks'])):?>
                            <div class="pb-2">
                                <?if(empty($val['review'])):?>
                                <?/*
                                <button type="button" class="btn btn-primary btn-add-review" data-toggle="modal" data-group="<?= $item['id'] ?>" data-lecture="<?= $val['id'] ?>" data-user="<?= $user['id'] ?>">Добавить ревью</button>
                                <hr>*/?>
                                <?else:?>
                                <div class="row">
                                    <div class="col-6">
                                        <a href="<?= $val['review']['video_url'] ?>" target="_blank" class="btn btn-primary btn-block">Видео</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="<?= $val['review']['file_url'] ?>" target="_blank" class="btn btn-primary btn-block">Файл</a>
                                    </div>
                                </div>
                                <?if(!empty($val['review']['text'])):?>
                                <div class="row">
                                    <div class="col-12 pt-2">
                                        <p><?= $val['review']['text'] ?></p>
                                    </div>
                                </div>
                                <?endif;?>
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn btn-secondary btn-xs bnt-remove-review" data-id="<?= $val['review']['id'] ?>">Удалить ревью</button>
                                    </div>
                                </div>
                                <hr>
                                <?endif;?>
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
                                    <?foreach($val['homeworks'] as $hw):?>
                                    <tr <?= ($hw['is_new']) ? 'class="table-danger"' : '' ?>>
                                        <td><?= date(DATE_FORMAT_FULL, $hw['ts_timestamp']) ?></td>
                                        <td><?= $hw['comment'] ?></td>
                                        <td class="text-right">
                                            <a href="./?user=<?= $user['id'] ?>&lecture=<?= $val['id'] ?>&action=download&target=homework" class="d-inline-block btn btn-primary btn-xs" target="_blank">Скачать</a>
                                        </td>
                                    </tr>
                                    <?endforeach;?>
                                </tbody>
                            </table>
                            <?else:?>
                            <div class="text-center">Нет загруженных файлов</div>
                            <?endif;?>
                        </div>

                        <?$n++;?>
                        <?endforeach;?>
                    </div>
                </div>
                <?endif;?>
                <?else:?>
                <h3 class="text-center">Выберите ученика</h3>
                <?endif;?>
            </div>
        </div>
        <?endif;?>
    </div>
    <div class="col-6">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title pt-2">Онлайн встречи</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="150">Дата</th>
                            <th>Название</th>
                            <th class="text-right" width="150">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($streams)):?>
                        <?php foreach($streams as $val):?>
                        <tr>
                            <td><?= date(DATE_FORMAT_FULL, strtotime($val['ts'])) ?></td>
                            <td>
                                <a href="/admin/streams/item/<?= $val['id'] ?>/" target="_blank"><?= $val['name'] ?></a>
                            </td>
                            <td class="text-right">
                                <?if($val['status'] == 0):?>
                                <span class="badge badge-success">В процессе</span>
                                <?elseif($val['status'] == -1):?>
                                <span class="badge badge-danger">Завершено</span>
                                <?elseif($val['status'] == 2):?>
                                <span class="badge badge-info">Сегодня</span>
                                <?else:?>
                                <span class="badge badge-warning">Скоро</span>
                                <?endif;?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <?php else:?>
                        <tr>
                            <td colspan="3">Список встреч пуст</td>
                        </tr>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title pt-2">Статистика</h3>
            </div>
            <div class="card-body">
                <h3><?= number_format($statTotal, 2, '.', ' ') ?> <?= PRICE_CHAR ?></h3>
                <canvas id="chart-income"></canvas>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="group--add-user-modal" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Добавить ученика</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="group_id" value="<?= $item['id'] ?>">
                    <input type="hidden" name="type" value="<?= $item['type'] ?>">
                    <div class="form-group users-block">
                        <div>
                            <select name="user" class="form-controll" id="select2-users" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Начать обучение с месяца</label>
                        <select name="month" class="form-control">
                            <option value="0">-- нет --</option>
                            <?php foreach($groupMonth as $row): ?>
                                <option value="<?=$row['number']?>">Месяц <?=$row['number']?> (<?=$row['end']?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="group--user-move" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Перевод ученика</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="group" value="<?= $item['id'] ?>">
                    <input type="hidden" name="user" value="">
                    <div class="form-group">
                        <label>Группа</label>
                        <select name="new_group" class="form-control">
                            <option value="0">-- нет --</option>
                            <?php foreach($groups as $row): ?>
                                <?php if ($row['id'] === $item['id'] /*|| $row['type'] !== $item['type']*/) continue;?>
                                    <option value="<?=$row['id']?>"><?=$row['title']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Начать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= TEMPLATE_DIR ?>/main_v1/vendors/chart.js/Chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function (event) {
        drawChart($("#chart-income"), 'Доход', [<?= $stat['labels'] ?>], [<?= $stat['values'] ?>]);
    });
</script>