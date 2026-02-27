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
		<td><?php 
			if (isset($word['user'])) {
				echo h($word['user']['first_name']) . ' ' . h($word['user']['last_name']) . ' (' . h($word['user']['email']) . ')';
			} else {
				echo h($word['full_name']) . ' (' . h($word['email'] . ')');
			}
				?></td>
		<td><?php echo h($word['created'].' ('.$this->Time->format($word['created'], [\IntlDateFormatter::FULL, \IntlDateFormatter::SHORT], false, 'America/Los_Angeles').')');?></td>
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
		<th>Submitted By</th>
		<th>Submitted On</th>
		<th></th>
	</tr>

<?php foreach ($pendingPronunciations as $word): ////$word = $word['Edit'];  ?> 

	<tr>
		<td><?php echo h($word->word ? $word->word->spelling : 'Unknown word');?></td>
		<td><?php echo h($word['spelling']);?></td>
		<td><?php 
			if (!empty($word->submitting_user)) {
				echo h($word->submitting_user['first_name']) . ' ' . h($word->submitting_user['last_name']) . ' (' . h($word->submitting_user['email']) . ')';
			} else {
				echo 'Recording made anonymously';
			}
		?></td>
		<td><?php echo h($this->Time->format($word['created'], \IntlDateFormatter::FULL, 'America/Los_Angeles'));?></td>

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
		<th></th>
	</tr>

<?php foreach ($noPronunciations as $word): ////$word = $word['Edit'];  ?> 

	<tr>
		<td><?php echo h($word['spelling']);?></td>	
		<td><?php echo $this->Html->link('View Entry', '/words/edit/'.$word['word_id']);?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>
<?php if ('superuser' == $userLevel):?>
<h3>Pending Sentence Recordings</h3>
<table>
	<tr>
		<th>Word</th>
		<th>Sentence</th>
		<th># Pending</th>
		<th></th>
	</tr>

<?php 
$alreadyDisplayed = [];

foreach ($pendingSentenceRecordings as $sentence){
	$i = 0;
	foreach($sentence['sentence_recordings'] as $sentRec){
		if ($sentRec['approved'] == 0) {
			$i = $i +1;
		}
	}
	if (!in_array($sentence['id'], $alreadyDisplayed)) {
		echo "<tr>";
		echo "<td>" . $sentence['word']['spelling'] . "</td><td>" . strip_tags($sentence['sentence']) . "</td><td align='right'>" . $i . "</td><td>" . $this->Html->link('Manage', ['prefix' => false, 'controller' => 'SentenceRecordings', 'action' => 'manage', $sentence['word_id'] , $sentence['id']]) . "</td>";
		array_push($alreadyDisplayed, $sentence['id']);
		echo "</tr>";
	}
	
} ?>
<?php endif; ?>