<div class="mb-4">
    <h3 class="mb-4">Обращения</h3>
    <div class="card">
        <div class="card-body">
            <?= showError($error) ?>
            <form action="" method="post">
                <input type="hidden" name="<?= $csrf['key'] ?>" value="<?= $csrf['value'] ?>">
                <div class="form-group">
                    <textarea name="text" class="form-control" placeholder="Текст обращения" rows="5"><?= htmlspecialchars($post['text'] ?? '') ?></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (count($items)): ?>
    <?php foreach ($items as $row): ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="header row mb-2">
                    <div class="col-8"><b><?= $row['date'] ?></b></div>
                    <div class="col-4 text-right">
                        <span class="badge badge-<?= $row['status_info']['class'] ?>"><?= $row['status_info']['text'] ?></span>
                    </div>
                </div>
                <div class="text"><?= character_limiter(htmlspecialchars(strip_tags($row['text'])), 200) ?></div>
                <hr>
                <div class="row mt-2">

                    <div class="col-8">
                        <span><b><?= ($row['message_count'] ?? 0) ?> сообщени<?= num2word(($row['message_count'] ?? 0), ['е', 'я', 'й']) ?></b></span>
                        <?php if(in_array($row['id'], $notificationsIds)): ?>
                            <?php $this->notifications->showPoint()?>
                        <?php endif;?>
                    </div>
                    <div class="col-4 text-right">
                        <a href="/support/ticket/<?= $row['code'] ?>/" class="btn btn-outline-primary btn-sm">Просмотр</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>