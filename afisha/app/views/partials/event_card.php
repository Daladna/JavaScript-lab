<article class="card">
    <div class="card-top">
        <span class="badge"><?= e($event['category_name']) ?></span>
        <span class="badge badge-gray"><?= e(format_label($event['format'])) ?></span>
    </div>
    <h3><?= e($event['title']) ?></h3>
    <p class="meta"><?= e(format_date($event['event_date'])) ?> · <?= e($event['place']) ?></p>
    <p><?= e(mb_strimwidth($event['description'], 0, 140, '…', 'UTF-8')) ?></p>
    <p class="card-bottom">
        <strong><?= e(format_price($event['price'])) ?></strong>
        <span class="meta"><?= (int)$event['age_limit'] ?>+ · автор: <?= e($event['username']) ?></span>
    </p>
</article>
