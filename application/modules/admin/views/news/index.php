<div class="row mb-4">
    <div class="col-6">
        <h3>Новости</h3>
    </div>
    <div class="col-6 text-right">
        <a href="./add/" class="btn btn-primary">Добавить</a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding-top: 30px;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="60">Id</th>
                    <th width="160">Дата</th>
                    <th>Название</th>
                    <th class="text-right" width="200">Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item):?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['ts'] ?></td>
                    <td><?= $item['title'] ?></td>
                    <td class="text-right">
                        <a href="./edit/<?= $item['id'] ?>/" class="btn btn-xs btn-primary d-inline-block" title="Редактировать">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <a href="./delete/<?= $item['id'] ?>" type="submit" class="btn btn-xs btn-danger delete-item-btn" title="Удалить">
                            <i class="mdi mdi-delete"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>