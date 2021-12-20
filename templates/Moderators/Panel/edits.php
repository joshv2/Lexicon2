<?php function status_to_words($approved){
	switch ($approved) {
		case 0:
			return "Pending";
		case 1:
			return "Approved";
		case -1:
			return "Denied";
	}
}
?>
<?php if ('superuser' == $userLevel):?>
<h2>Pending Suggestions</h2>

<?php if (empty($pendingSuggestions)):?>

<p>There are no pending suggestions.</p>

<?php else: ?>

<table>
	<tr>
		<th>Word</th>
		<th>Contributor Name</th>
		<th>Suggested Changes</th>
		<th>Submitted On</th>
		<th>Dismiss</th>
	</tr>

<?php foreach ($pendingSuggestions as $suggestion): //$word = $word['Edit']; ?>

	<tr>
		<td><?php echo h($suggestion['word']['spelling']);?></td>
		<td><?php echo h($suggestion['full_name']);?> (<?php echo h($suggestion['email']);?>)</td>
		<td><?php echo h($suggestion['suggestion']);?></td>
		<td><?php echo h($suggestion['created']);?></td>
		<td><?php echo 
			$this->Form->postLink(
                'Delete',
                ['prefix' => false, 'controller' => 'Suggestions', 'action' => 'delete', $suggestion['id']],
                ['confirm' => 'Are you sure?']);?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>

<h2>Proposed Words</h2>

<?php if (empty($newWords)):?>

<p>There are no new submissions.</p>

<?php else: ?>

<table>
	<tr>
		<th>Word</th>
		<th>Submitted By</th>
		<th>Submitted On</th>
		<th></th>
	</tr>

<?php foreach ($newWords as $word): ////$word = $word['Edit'];  ?> 

	<tr>
		<td><?php echo h($word['spelling']);?></td>
		<td><?php echo h($word['full_name']);?> (<?php echo h($word['email']);?>)</td>
		<td><?php echo h($word['created'].' ('.$this->Time->format($word['created'], [\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT], null, 'America/Los_Angeles').')');?></td>
		<td><?php echo $this->Html->link('View Entry', '/words/edit/'.$word['id']);?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>


<?php endif;?>

<?php if ('superuser' == $userLevel):?>
<h2>Pending Pronunciations</h2>
	<table>
	<tr>
		<th>For Word</th>
		<th>Pronunciation Spelling</th>
		<th>Submitted On</th>
		<th></th>
	</tr>

<?php foreach ($pendingPronunciations as $word): ////$word = $word['Edit'];  ?> 

	<tr>
		<td><?php echo h($word->word->spelling);?></td>
		<td><?php echo h($word['spelling']);?></td>
		<td><?php echo h($this->Time->format($word['created'], [\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT], null, 'America/Los_Angeles'));?></td>

		<td>
			<?php echo $this->Html->link('Manage', ['prefix' => false, 'controller' => 'pronunciations', 'action' => 'manage', $word->word->id]); ?>
				
		</td>
	</tr>
<?php endforeach; ?>
</table>


<?php endif;?>

<h2>Missing Pronunciations</h2>

<?php if (empty($noPronunciations)):?>

<p>All approved words have pronunciations.</p>

<?php else: ?>

<table>
	<tr>
		<th>Word</th>
		<th>Submitted By</th>
		<th>Submitted On</th>
		<th></th>
	</tr>

<?php foreach ($noPronunciations as $word): ////$word = $word['Edit'];  ?> 

	<tr>
		<td><?php echo h($word['spelling']);?></td>
		<td><?php echo h($word['full_name']);?> (<?php echo h($word['email']);?>)</td>
		<td><?php echo h($word['created'].' ('.$this->Time->format($word['created'], [\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT], null, 'America/Los_Angeles').')');?></td>
		<td><?php echo $this->Html->link('View Entry', '/words/edit/'.$word['id']);?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>
