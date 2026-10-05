<?= $this->extend("layout/template"); ?>

<?= $this->section("content"); ?>

<div class="container py-4">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4 gap-3">
        <div>
            <h1 class="display-6 fw-bold text-dark mb-1">Paříž - Nice</h1>
            
        </div>
        <div>
            <button class="btn btn-success fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPridatRocnik">
                Přidat ročník
            </button>
        </div>
    </div>

   

    <?php foreach ($rocniky as $year): ?>
        <div class="card border border-light-subtle shadow-sm overflow-hidden mb-4">

            <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 py-3">
                <h2 class="h5 fw-bold text-dark mb-0">
                    <?= esc($year->real_name) ?>
                    <span class="text-secondary fw-normal"><?= (int) $year->year ?></span>
                </h2>
                <div class="d-flex flex-wrap align-items-center gap-2 small">
                    <span class="text-secondary">
                        <?= date('d.m.Y', strtotime($year->start_date)) ?> – <?= date('d.m.Y', strtotime($year->end_date)) ?>
                    </span>
                    <span class="badge bg-secondary-subtle text-secondary-emphasis px-2 py-1">
                        <?= number_format(round((float) $year->total_distance), 0, ',', ' ') ?> km
                    </span>
                    <span class="badge bg-secondary-subtle text-secondary-emphasis px-2 py-1">
                        etap: <?= count($year->stages) ?>
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle ">
                    <thead class="table-light border-bottom">
                    <tr class="text-secondary text-uppercase small">
                        <th class="p-3">Datum</th>
                        <th class="p-3">Délka</th>
                        <th class="p-3">Převýšení</th>
                        <th class="p-3">Typ etapy</th>
                        <th class="p-3">Vítěz etapy</th>
                        <th class="p-3 text-end">Pořadí</th>
                    </tr>
                    </thead>
                    <tbody>
            

                    <?php foreach ($year->stages as $stage): ?>
                        <tr>
                            <td class="p-3"><?= date('d.m.Y', strtotime($stage->date)) ?></td>
                            <td class="p-3"><?= number_format((float) $stage->distance, 1, ',', ' ') ?> km</td>
                            <td class="p-3"><?= number_format((int) $stage->vertical_meters, 0, ',', ' ') ?> m</td>
                            <td class="p-3"><?= esc($stage->stage_type ?? '—') ?></td>
                            <td class="p-3 fw-semibold"><?= esc($stage->winner) ?></td>
                            <td class="p-3 text-end text-nowrap">
                                <a href="<?= base_url('etapa/' . $stage->id . '/vysledky/1') ?>" class="btn btn-sm btn-outline-success fw-semibold">V etapě</a>
                                <a href="<?= base_url('etapa/' . $stage->id . '/vysledky/4') ?>" class="btn btn-sm btn-outline-dark fw-semibold">Po etapě</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->include('rocniky/pridavani') ?>

<?= $this->endSection(); ?>
