<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sentence $sentence
 * @var int|null $wordId
 * @var array $words
 */

$initialDelta =
    $this->request->getData('sentence')
    ?? ($sentence->sentence_json ?? null)
    ?? '{"ops":[{"insert":"\n"}]}';
?>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Sentences'), ['action' => 'index', $wordId], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>

    <div class="column-responsive column-80">
        <div class="sentences form content">
            <?= $this->Form->create($sentence) ?>
            <fieldset>
                <legend><?= __('Add Sentence') ?></legend>

                <?php
                    if ($wordId !== null) {
                        echo $this->Form->control('word_id', ['type' => 'hidden', 'value' => $wordId]);
                        echo '<p>' . h("Adding sentence for word ID: {$wordId}") . '</p>';
                    } else {
                        echo $this->Form->control('word_id', ['options' => $words]);
                    }

                    // Hidden input id MUST match editor id minus "editor-"
                    echo $this->Form->hidden('sentence', ['id' => 'sentence', 'value' => $initialDelta]);
                ?>

                <div class="editor-container">
                    <div id="editor-sentence"></div>
                </div>
            </fieldset>

            <?= $this->Form->button(__('Submit'), ['class' => 'button blue']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hidden = document.getElementById('sentence');
    const editor = document.getElementById('editor-sentence');
    if (!hidden || !editor || typeof Quill === 'undefined') return;

    const editorContainer = editor.closest('.editor-container') || editor.parentElement || document.body;

    function dedupeToolbars() {
        const toolbars = editorContainer.querySelectorAll('.ql-toolbar');
        for (let i = 1; i < toolbars.length; i++) toolbars[i].remove();
    }

    dedupeToolbars();
    const obs = new MutationObserver(() => dedupeToolbars());
    obs.observe(editorContainer, { childList: true, subtree: true });

    if (editor.__quill || editor.dataset.quillInitialized === '1' || editor.classList.contains('ql-container')) {
        dedupeToolbars();
        return;
    }
    editor.dataset.quillInitialized = '1';

    const quill = new Quill(editor, {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['link'],
                ['clean']
            ]
        }
    });

    function parseDeltaOps(raw) {
        const fallbackOps = [{ insert: "\n" }];
        if (!raw) return fallbackOps;
        try {
            const content = JSON.parse(raw);
            if (content && Array.isArray(content.ops)) return content.ops;
        } catch (e) {}
        return fallbackOps;
    }

    quill.setContents(parseDeltaOps(hidden.value));
    dedupeToolbars();

    const form = hidden.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            hidden.value = JSON.stringify(quill.getContents());
        });
    }
});
</script>
