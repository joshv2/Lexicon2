<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OriginsWord $link
 * @var int $wordId
 * @var array $origins
 */
?>
<section id="main" class="main">
    <div class="c">
        <div class="page-header group">
            <h2 class="left"><?= __('Add Origin') ?></h2>
            <div class="right">
                <?= $this->Html->link(
                    __('Back'),
                    ['action' => 'index', $wordId],
                    ['class' => 'button blue nl']
                ) ?>
            </div>
        </div>

        <div class="originsWords form content" style="max-width: 100%;">
            <?= $this->Form->create($link) ?>
            <?= $this->Form->control('word_id', ['type' => 'hidden', 'value' => $wordId]) ?>
            <p><?= h("Word ID: {$wordId}") ?></p>

            <?= $this->Form->control('origin_id', [
                'label' => __('Origin'),
                'options' => $origins,
                'empty' => __('(choose one)'),
                'onchange' => 'toggleOriginsOther(this)',
                'style' => 'width: 100%; max-width: 100%;'
            ]) ?>

            <div class="form-group-origins-other" id="origins-other-wrapper" style="display:none; max-width: 100%;">
                <?= $this->Form->control('origins_other_entry', [
                    'label' => __('Enter other origins separated by semicolon'),
                    'id' => 'origins-other-entry',
                    'required' => false,
                ]) ?>
            </div>

            <?= $this->Form->button(__('Submit'), ['class' => 'button blue']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</section>

<script>
function toggleOriginsOther(selectEl) {
    const otherBox = document.getElementById('origins-other-wrapper')
        || document.querySelector('.form-group-origins-other');
    const otherInput = document.getElementById('origins-other-entry');
    if (!selectEl || !otherBox || !otherInput) return;

    const isOther = String(selectEl.value) === '999';
    otherBox.style.display = isOther ? 'block' : 'none';
    if (isOther) {
        otherInput.setAttribute('required', 'required');
    } else {
        otherInput.removeAttribute('required');
    }
}

const initOriginOtherToggle = () => {
    const select = document.getElementById('origin-id')
        || document.querySelector('select[name="origin_id"]');
    if (!select) return;
    select.addEventListener('change', () => toggleOriginsOther(select));
    toggleOriginsOther(select);

};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initOriginOtherToggle);
} else {
    initOriginOtherToggle();
}
</script>