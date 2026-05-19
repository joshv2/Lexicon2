<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TypesWord $link
 * @var int $wordId
 * @var array $types
 */
?>
<section id="main" class="main">
    <div class="c">
        <div class="page-header group">
            <h2 class="left"><?= __('Add Type') ?></h2>
            <div class="right">
                <?= $this->Html->link(
                    __('Back'),
                    ['action' => 'indexByWord', $wordId],
                    ['class' => 'button blue nl']
                ) ?>
            </div>
        </div>

        <div class="typesWords form content" style="max-width: 100%;">
            <?= $this->Form->create($link) ?>
            <?= $this->Form->control('word_id', ['type' => 'hidden', 'value' => $wordId]) ?>
            <p><?= h("Word ID: {$wordId}") ?></p>

            <?= $this->Form->control('type_id', [
                'label' => __('Type'),
                'options' => $types,
                'empty' => __('(choose one)'),
                'onchange' => 'toggleTypesOther(this)',
                'style' => 'width: 100%; max-width: 100%;'
            ]) ?>

            <div class="form-group-types-other" id="types-other-wrapper" style="display:none; max-width: 100%;">
                <?= $this->Form->control('types_other_entry', [
                    'label' => __('Enter other groups separated by semicolon'),
                    'id' => 'types-other-entry',
                    'required' => false,
                ]) ?>
            </div>

            <?= $this->Form->button(__('Submit'), ['class' => 'button blue']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</section>

<script>
function toggleTypesOther(selectEl) {
    const otherBox = document.getElementById('types-other-wrapper')
        || document.querySelector('.form-group-types-other');
    const otherInput = document.getElementById('types-other-entry');
    if (!selectEl || !otherBox || !otherInput) return;

    const isOther = String(selectEl.value) === '999';
    otherBox.style.display = isOther ? 'block' : 'none';
    if (isOther) {
        otherInput.setAttribute('required', 'required');
    } else {
        otherInput.removeAttribute('required');
    }
}

const initTypeOtherToggle = () => {
    const select = document.getElementById('type-id')
        || document.querySelector('select[name="type_id"]');
    if (!select) return;
    select.addEventListener('change', () => toggleTypesOther(select));
    toggleTypesOther(select);

};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTypeOtherToggle);
} else {
    initTypeOtherToggle();
}
</script>