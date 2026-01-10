<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Definition $definition
 */
?>
<div class="edit-page-container">
    

    <div>
        <?php if (!empty($definition->word)): ?>
            <div class="word-header" style="margin-bottom: 20px;">
                <h2 style="margin:0;">
                    Editing Definition for:
                    <span style="color:#0066cc;"><?= h($definition->word->spelling) ?></span>
                </h2>

                <p style="margin:5px 0 15px 0; color:#666;">
                    Word ID: <?= h($definition->word->id) ?>
                </p>

                <?= $this->Html->link(
                    'View Word',
                    ['controller' => 'Words', 'action' => 'view', $definition->word->id],
                    ['style' => 'margin-bottom:10px; display:inline-block;']
                ) ?>
            </div>
        <?php endif; ?>
        <div class="definitions form content">

            <?= $this->Form->create($definition) ?>
            <fieldset>
                <legend><?= __('Edit Definition') ?></legend>

                <!-- Hidden fields -->
                <?= $this->Form->hidden('definition_json', ['id' => 'definition_json']) ?>
                <?= $this->Form->hidden('definition', ['id' => 'definition']) ?>

                <!-- Quill editor -->
                <label><strong>Definition</strong></label>
                <div id="quill-editor" style="height: 250px; background:#fff; border:1px solid #ccc;"></div>

            </fieldset>

            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<!-- Quill Includes -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // Initialize Quill
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Pre-populate Quill if JSON exists
    const existingJson = <?= json_encode($definition->definition_json ?? null) ?>;

    if (existingJson) {
        try {
            quill.setContents(JSON.parse(existingJson));
        } catch (e) {
            console.warn("Invalid Quill JSON, falling back to HTML.");
            quill.root.innerHTML = <?= json_encode($definition->definition ?? '') ?>;
        }
    } else {
        quill.root.innerHTML = <?= json_encode($definition->definition ?? '') ?>;
    }

    // On submit → update hidden form fields
    const form = document.querySelector('form');
    form.addEventListener('submit', function () {
        const delta = quill.getContents();
        const html = quill.root.innerHTML;

        document.getElementById('definition_json').value = JSON.stringify(delta);
        document.getElementById('definition').value = html;
    });

});
</script>