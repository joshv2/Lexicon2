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
            <?= $this->Form->create($sentence, ['id' => 'add_form']) ?>
            <fieldset>
                <legend><?= __('Edit Sentence') ?></legend>
                <?php
                    echo $this->Form->control('word_id', ['options' => $words]);

                    // IMPORTANT: id="sentence" so JS can load it
                    echo $this->Form->hidden('sentence', ['id' => 'sentence', 'value' => $initialDelta]);
                ?>

                <div class="editor-container">
                    <div id="editor-sentence"></div>
                    <textarea id="sentence-fallback" style="display:none;width:100%;min-height:240px"></textarea>
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
    const fallback = document.getElementById('sentence-fallback');
    if (!hidden || !editor) return;

    function deltaToPlainText(raw) {
        if (!raw) return '';
        try {
            const parsed = JSON.parse(raw);
            if (!parsed || !Array.isArray(parsed.ops)) return '';
            return parsed.ops.map(op => (typeof op.insert === 'string' ? op.insert : '')).join('').replace(/\n+$/,'');
        } catch (e) {
            return '';
        }
    }

    // If Quill can't load, show a plain textarea and still submit content.
    // When Quill is available, the global app script (webroot/js/addform.js)
    // initializes #editor-sentence and syncs it into #sentence on submit.
    if (typeof Quill === 'undefined') {
        if (!fallback) return;
        fallback.style.display = '';
        fallback.value = deltaToPlainText(hidden.value);

        const form = hidden.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                hidden.value = JSON.stringify({ ops: [{ insert: (fallback.value || '') + "\n" }] });
            });
        }
    }
});
</script>

<style>
    .editor-container {
        width: 100%;
    }
    #editor-sentence,
    #editor-sentence .ql-toolbar,
    #editor-sentence .ql-container {
        width: 100%;
    }
    #editor-sentence .ql-editor {
        min-height: 240px;
    }
</style>
