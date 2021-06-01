<div class="row mb-4">
    <div class="col-6">
        <h3>Новая лекция</h3>
    </div>
    <div class="col-6 text-right">
        <a href="../" class="btn btn-secondary">Назад</a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding-top: 30px;">
        <div class="col-12">
            <?= showError($error); ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?= $csrf['key'] ?>" value="<?= $csrf['value'] ?>">
                <div class="form-group">
                    <input type="hidden" name="active" value="0">
                    <div class="form-check d-inline-block m-0">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-control" name="active" value="1" <?= $item['active'] ? 'checked="true"' : '' ?>>
                            Доступен?
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="fname">Название</label>
                            <input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?= $item['name'] ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="ftype">Тип</label>
                            <select name="type" id="ftype" class="form-control">
                                <?php foreach($types as $key => $val):?>
                                <option value="<?= $key ?>" <?= ($item['type'] === $key) ? 'selected="true"' : '' ?> ><?= $val['title'] ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="fsort">Сортировка</label>
                            <input type="text" name="sort" id="fsort" class="form-control" placeholder="Сортировка" value="<?= $item['sort'] ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="fvideo">Видео</label>
                            <input type="text" name="video" id="fvideo" class="form-control" placeholder="Видео" value="<?= $item['video'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="fdescription">Описание</label>
                            <textarea name="description" id="fdescription" class="form-control" placeholder="Описание" rows="10"><?= $item['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="ftask">Задание</label>
                            <textarea name="task" id="ftask" class="form-control" placeholder="Задание" rows="10"><?= $item['task'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12">Файлы</label>
                    <?php for($i = 0; $i < 4; $i++):?>
                    <div class="col-3">
                        <input type="file" name="files[]" class="file-upload-default">
                        <div class="input-group">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Загрузка файла">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-secondary" type="button">Выбрать</button>
                            </span>
                        </div>
                    </div>
                    <?php endfor;?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>