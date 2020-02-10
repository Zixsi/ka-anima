<div class="row mb-4">
    <div class="col-6">
        <h3>Редактировать промокод</h3>
    </div>
    <div class="col-6 text-right">
        <a href="../../" class="btn btn-secondary">Назад</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="col-xs-12">
            <?=showError($error);?>
            <form action="" method="POST" id="promocode-form">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Код <small>(символы a-zA-Z, подчеркивание и тире)</small></label>
                            <input type="text" name="code" class="form-control" placeholder="Код" value="<?=set_value('code', $item['code'], true)?>" disabled="true">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Количество</label>
                            <input type="number" name="count" class="form-control" placeholder="Количество" min="0" value="<?=set_value('count', $item['count'], true)?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Значение, %</label>
                            <input type="number" name="value" class="form-control" placeholder="Значение" min="0" max="100" value="<?=set_value('value', $item['value'], true)?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Тип предложения</label>
                            <select name="target_type" class="form-control">
                                <option value="" <?=set_select('target_type', '', true)?>>Все</option>
                                <?php foreach ($types as $key => $value) :?>
                                    <option value="<?=$key?>" <?=set_select('target_type', $key, ($item['target_type'] === $key))?>><?=$value?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Предложение</label>
                            <select name="target_id" class="form-control">
                                <option value="0" <?=set_select('target_id', 0, true)?>>Нет</option>
                                <?php if ($course_list) :?>
                                    <optgroup label="Курсы" data-type="course" <?=($item['target_type'] !== 'course')?'style="display: none;"':''?>>
                                        <?php foreach ($course_list as $row) :?>
                                            <option value="<?=$row['id']?>" <?=set_select('target_id', $row['id'], ($item['target_type'] === 'course' && $row['id'] == $item['target_id']))?>><?=$row['name']?></option>
                                        <?php endforeach;?>
                                    </optgroup>
                                <?php endif;?>

                                <?php if ($course_list) :?>
                                    <optgroup label="Мастерская" data-type="workshop" <?=($item['target_type'] !== 'workshop')?'style="display: none;"':''?>>
                                        <?php foreach ($workshop_list as $row) :?>
                                            <option value="<?=$row['id']?>" <?=set_select('target_id', $row['id'], ($item['target_type'] === 'workshop' && $row['id'] == $item['target_id']))?>><?=$row['title']?></option>
                                        <?php endforeach;?>
                                    </optgroup>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Тип подписки</label>
                            <select name="subscr_type" class="form-control">
                                <option value="" <?=set_select('target_type', '', true)?>>Нет</option>
                                <?php foreach ($subscr_types as $key => $value) :?>
                                    <option value="<?=$key?>" <?=set_select('subscr_type', $key, ($item['subscr_type'] === $key))?>><?=$value?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Дата начала</label>
                            <input type="text" name="date_from" class="form-control datetimepicker2" value="<?=set_value('date_from', ($item['date_from'] === '0000-00-00 00:00:00'?'':$item['date_from']), true)?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Дата завершения</label>
                            <input type="text" name="date_to" class="form-control datetimepicker2" value="<?=set_value('date_to', ($item['date_to'] === '0000-00-00 00:00:00'?'':$item['date_to']), true)?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>