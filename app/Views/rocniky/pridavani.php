<?php
/** @var array $male_elite_races */
$reopen = session()->getFlashdata('errors') && session()->has('_ci_old_input');
?>
<div class="modal fade" id="modalPridatRocnik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <?= form_open_multipart(base_url('rocniky/create'), ['class' => 'w-100']) ?>
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold text-dark">Přidat nový ročník</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
                </div>
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label for="real_name" class="form-label fw-semibold small text-secondary">Název ročníku:</label>
                        <input type="text" id="real_name" name="real_name" class="form-control border-light-subtle"
                               value="<?= esc(old('real_name', '', false)) ?>" maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="id_race" class="form-label fw-semibold small text-secondary">Závod:</label>
                        <select id="id_race" name="id_race" class="form-select border-light-subtle" required>
                            <option value="">Vyberte mužský závod kategorie E...</option>
                            <?php foreach ($male_elite_races as $item): ?>
                                <option value="<?= (int) $item->id ?>"
                                    <?= (string) old('id_race', (string) ($id_race ?? '')) === (string) $item->id ? 'selected' : '' ?>>
                                    <?= esc($item->default_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    
                </div>
                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none fw-semibold" data-bs-dismiss="modal">Zavřít</button>
                    <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm">Přidat ročník</button>
                </div>
            </div>
        <?= form_close() ?>
    </div>
</div>

<?php if ($reopen): ?>
<script {csp-script-nonce}>
document.addEventListener('DOMContentLoaded', function () {
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPridatRocnik')).show();
});
</script>
<?php endif; ?>
