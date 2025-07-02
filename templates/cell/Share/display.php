<?php
$currentUrl = $this->Url->build(null, ['fullBase' => true]);
$wordText = h($word->spelling);
$encodedText = urlencode("Check out this word: " . $wordText);
$encodedUrl = urlencode($currentUrl);
?>

<a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" 
    target="_blank" 
    aria-label="Share on Facebook">
<img src="/img/share_icons/facebook.svg">
    <span class="shareLink">
        <?= $dropdown ? 'Facebook' : '' ?>
    </span>
</a>
<a href="https://x.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= $encodedText ?>" 
    target="_blank" 
    aria-label="Share on X">
<img src="/img/share_icons/x.svg">
    <span class="shareLink">
        <?= $dropdown ? 'X' : '' ?>
    </span>
</a>
<a href="https://api.whatsapp.com/send?text=<?= $encodedText ?>%20<?= $encodedUrl ?>" 
    target="_blank" 
    aria-label="Share on WhatsApp">
    <img src="/img/share_icons/whatsapp.svg">
    <span class="shareLink">
        <?= $dropdown ? 'WhatsApp' : '' ?>
    </span>
</a>
<a href="https://bsky.app/intent/compose?text=<?= $encodedText ?>%20<?= $encodedUrl ?>" 
    target="_blank" 
    aria-label="Share on Bluesky">
    <img src="/img/share_icons/bluesky.svg">
    <span class="shareLink">
        <?= $dropdown ? 'Bluesky' : '' ?>
    </span>
</a>