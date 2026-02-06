<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sentence $sentence
 */

$sitelang = $this->request->getAttribute('sitelang');

$fallbackText = trim(strip_tags((string)($sentence->sentence ?? '')));
$fallbackDelta = '{"ops":[{"insert":"' . addslashes(str_replace(["\r\n","\r","\n"], "\\n", $fallbackText)) . '\n"}]}';

$initialDelta =
    $this->request->getData('sentence')
    ?? ($sentence->sentence_json ?? null)
    ?? $fallbackDelta;
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sentence->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sentence->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Sentences'), ['action' => 'index', $sentence->word_id], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sentences form content">
            <?= $this->Form->create($sentence) ?>
            <fieldset>
                <legend><?= __('Edit Sentence') ?></legend>
                <?php
                    echo $this->Form->control('word_id', ['options' => $words]);

                    // IMPORTANT: id="sentence" so JS can load it
                    echo $this->Form->hidden('sentence', ['id' => 'sentence', 'value' => $initialDelta]);
                ?>

                <div class="editor-container">
                    <div id="editor-sentence"></div>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
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

    // Keep deduping even if something else initializes Quill later
    dedupeToolbars();
    const obs = new MutationObserver(() => dedupeToolbars());
    obs.observe(editorContainer, { childList: true, subtree: true });

    // Prevent *this* template from double-initializing
    if (editor.__quill || editor.dataset.quillInitialized === '1' || editor.classList.contains('ql-container')) {
        // Still ensure we don't end up with two toolbars
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
