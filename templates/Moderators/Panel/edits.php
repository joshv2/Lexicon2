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

<h2>New Words</h2>

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

<h2>Your Words</h2>

<?php if (empty($submittedWords)):?>

<p>You have made no submissions or they have all been deleted.</p>

<?php else: ?>

<table>
	<tr>
		<th>Word</th>
		<th>Submitted On</th>
		<th>Status</th>
		<th></th>
	</tr>

<?php foreach ($submittedWords as $word): ////$word = $word['Edit'];  ?> 

	<tr>
		<td><?php echo h($word['spelling']);?></td>
		<td><?php echo h($word['created'].' ('.$this->Time->format($word['created'], [\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT], null, 'America/Los_Angeles').')');?></td>
		<td><?php echo status_to_words($word['approved']);?></td>
		<td><?php if(1==$word['approved']) {
			echo $this->Html->link('View Entry', ['prefix' => false, 'controller' => 'words', 'action' => 'view', $word['id']]);
		 } ?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>


<h2>Your Pronunciations</h2>

<?php if (empty($submittedPronunciations)):?>

<p>You have made no submissions or they have all been deleted.</p>

<?php else: ?>

<table>
	<tr>
		<th>For Word</th>
		<th>Pronunciation Spelling</th>
		<th>Submitted On</th>
		<th>Status</th>
	</tr>

<?php foreach ($submittedPronunciations as $word): ////$word = $word['Edit'];  ?> 

	<tr>
		<td><?php echo h($word->word->spelling);?></td>
		<td><?php echo h($word['spelling']);?></td>
		<td><?php echo h($word['created'].' ('.$this->Time->format($word['created'], [\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT], null, 'America/Los_Angeles').')');?></td>

		<td>
			<?php if(1==$word->approved && 1 == $word->word->approved) {
				echo "Approved: " . $this->Html->link('View Entry', ['prefix' => false, 'controller' => 'words', 'action' => 'view', $word->word->id]);	
			} elseif (0 == $word->approved) {
				echo "Pending Approval";
			} elseif (-1 == $word->approved) {
				echo "Denied" . $word->notes;
			}
			?>
				
		</td>
	</tr>
<?php endforeach; ?>

</table>

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

<h2>All Pronunciations</h2>
	<table>
	<tr>
		<th>For Word</th>
		<th>Pronunciation Spelling</th>
		<th>Submitted On</th>
		<th>Submitted By</th>
		<th>Status</th>
		<th>Last Change Date</th>
		<th>Approved By</th>
		<th></th>
	</tr>

<?php foreach ($allPronunciations as $word): ////$word = $word['Edit'];  ?> 

	<tr>
		<td><?php echo h($word->word->spelling);?></td>
		<td><?php echo h($word['spelling']);?></td>
		<td><?php echo h($this->Time->format($word['created'], [\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT], null, 'America/Los_Angeles'));?></td>
		<td><?php 
			if(isset($word->submitting_user)){
				echo h($word->submitting_user['first_name']) . ', ' . h($word->submitting_user['last_name']) . " (" . h($word->submitting_user['email']) . ")";
			} else {
				echo "Recording made anonymously";
			} ?>
		</td>
		<td>
			<?php if(1==$word->approved && 1 == $word->word->approved) {
				echo "Approved: " . $this->Html->link('View Entry', ['prefix' => false, 'controller' => 'words', 'action' => 'view', $word->word->id]);	
			} elseif (0 == $word->approved) {
				echo "Pending Approval";
			} elseif (-1 == $word->approved) {
				echo "Denied" . $word->notes;
			}
			?>
				
		</td>
		<td><?php echo h($this->Time->format($word['approved_date'], [\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT], null, 'America/Los_Angeles'));?></td>
		<td><?php 
			if(isset($word->approving_user)){
				echo h($word->approving_user['first_name']) . ', ' . h($word->approving_user['last_name']) . " (" . h($word->approving_user['email']) . ")";
			} else {
				echo "Not yet approved";
			} ?>
		</td>
		<td>
			<?php echo $this->Html->link('Manage', ['prefix' => false, 'controller' => 'pronunciations', 'action' => 'manage', $word->word->id]); ?>
				
		</td>
	</tr>
<?php endforeach; ?>

</table>
<?php endif;?>