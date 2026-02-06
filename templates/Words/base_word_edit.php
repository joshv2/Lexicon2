<?php
/**
 * @var \App\Model\Entity\Word $word
 * @var \Cake\View\View $this
 */
?>
<?= $this->Html->css('edit-word') ?>
<div class="edit-word-container">
<h1>Edit Word</h1>

<?= $this->Form->create($word) ?>

<div class="field-group">
    <?= $this->Form->control('spelling', [
        'label' => 'Spelling',
        'required' => true,
    ]) ?>
</div>

<hr>

<h3>Etymology</h3>
<div id="etymology-editor" style="height: 200px;"></div>

<?= $this->Form->hidden('etymology') ?>
<?= $this->Form->hidden('etymology_json') ?>

<hr>

<h3>Notes</h3>
<div id="notes-editor" style="height: 200px;"></div>

<?= $this->Form->hidden('notes') ?>
<?= $this->Form->hidden('notes_json') ?>

<hr>

<?= $this->Form->button(__('Save')) ?>
<?= $this->Form->end() ?>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // -------- Initialize Editors --------
    const etymologyEditor = new Quill('#etymology-editor', { theme: 'snow' });
    const notesEditor     = new Quill('#notes-editor', { theme: 'snow' });

    // Safely inject server values as JS strings
    const etymologyJson = <?= json_encode((string)($word->etymology_json ?? '')) ?>;
    const notesJson     = <?= json_encode((string)($word->notes_json ?? '')) ?>;

    const etymologyText = <?= json_encode((string)($word->etymology ?? '')) ?>;
    const notesText     = <?= json_encode((string)($word->notes ?? '')) ?>;

    function loadEditor(editor, deltaJsonString, fallbackText) {
        if (deltaJsonString) {
            try {
                const delta = JSON.parse(deltaJsonString);
                editor.setContents(delta);
                return;
            } catch (e) {
                // If stored JSON is invalid, fall back to plain text
                console.warn('Failed to parse Quill JSON; falling back to text.', e);
            }
        }
        if (fallbackText) {
            editor.setText(fallbackText);
        }
    }

    // -------- Load initial values --------
    loadEditor(etymologyEditor, etymologyJson, etymologyText);
    loadEditor(notesEditor, notesJson, notesText);

    // -------- Sync Quill -> hidden inputs --------
    // Be tolerant of CakePHP name/id variations.
    const etymologyInput = document.getElementById('etymology') || document.querySelector("input[name='etymology']");
    const notesInput     = document.getElementById('notes') || document.querySelector("input[name='notes']");
    const etymologyJsonInput = document.getElementById('etymology-json') || document.querySelector("input[name='etymology_json']");
    const notesJsonInput     = document.getElementById('notes-json') || document.querySelector("input[name='notes_json']");

    const form = (etymologyInput && etymologyInput.form)
        || (notesInput && notesInput.form)
        || document.querySelector(".edit-word-container form")
        || document.querySelector("form");

    function syncQuillToInputs() {
        const etyDelta = etymologyEditor.getContents();
        const nDelta   = notesEditor.getContents();

        const etyDeltaJson = JSON.stringify(etyDelta);
        const nDeltaJson   = JSON.stringify(nDelta);

        // For server-side processing, we post the delta JSON in the base fields.
        if (etymologyInput) etymologyInput.value = etyDeltaJson;
        if (notesInput) notesInput.value = nDeltaJson;

        // Also keep *_json in sync (useful for backwards compatibility / debugging).
        if (etymologyJsonInput) etymologyJsonInput.value = etyDeltaJson;
        if (notesJsonInput) notesJsonInput.value = nDeltaJson;
    }

    etymologyEditor.on('text-change', syncQuillToInputs);
    notesEditor.on('text-change', syncQuillToInputs);

    // Ensure hidden inputs are initialized even if user submits without editing.
    syncQuillToInputs();

    if (form) {
        form.addEventListener("submit", function() {
            syncQuillToInputs();
        });
    }
});
</script>