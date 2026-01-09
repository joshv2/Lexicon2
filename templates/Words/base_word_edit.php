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

    // -------- Before submit: write JSON + plain text into hidden inputs --------
    const form = document.querySelector("form");
    form.addEventListener("submit", function() {
        const etyDelta   = etymologyEditor.getContents();
        const notesDelta = notesEditor.getContents();

        document.querySelector("input[name='etymology_json']").value = JSON.stringify(etyDelta);
        document.querySelector("input[name='notes_json']").value     = JSON.stringify(notesDelta);

        document.querySelector("input[name='etymology']").value =
            etymologyEditor.getText().trim();

        document.querySelector("input[name='notes']").value =
            notesEditor.getText().trim();
    });
});
</script>