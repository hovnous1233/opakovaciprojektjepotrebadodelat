<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container py-4">

    <div class="border-bottom pb-3 mb-4">
        <a href="<?= base_url('rocniky/' . $stage->id_race) ?>" class="text-decoration-none small fw-semibold">&larr; Zpět na ročníky</a>
        <h1 class="display-6 fw-bold text-dark mt-2 mb-1"><?= $type_result === 1 ? 'Pořadí v etapě' : 'Pořadí po etapě' ?></h1>
        <p class="text-muted small mb-0">
            <?= esc($stage->real_name) ?> <?= (int) $stage->year ?>
            · <?= date('d.m.Y', strtotime($stage->date)) ?>
            · <?= number_format((float) $stage->distance, 1, ',', ' ') ?> km
        </p>
    </div>

    <?php if (empty($results)): ?>
        <div class="alert alert-info">Pro tuto etapu nejsou uložené žádné výsledky.</div>
    <?php else: ?>
        <div class="card border border-light-subtle shadow-sm overflow-hidden mb-4">
            <table class="table align-middle ">
                <thead class="table-light border-bottom">
                <tr class="text-secondary text-uppercase small">
                    <th class="p-3">#</th>
                    <th class="p-3">Jezdec</th>
                    <th class="p-3">Čas</th>
                    <th class="p-3">Bonifikace</th>
                    <th class="p-3">Body</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td class="p-3"><span class="badge bg-secondary-subtle text-secondary-emphasis px-2 py-1"><?= esc($result->rank) ?></span></td>
                        <td class="p-3 fw-semibold"><?= esc($result->rider_name ?? '—') ?></td>
                        <td class="p-3"><?= esc($result->time ?? '—') ?></td>
                        <td class="p-3"><?= esc($result->bonification ?? '—') ?></td>
                        <td class="p-3"><?= $result->point !== null ? esc($result->point) : '—' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
