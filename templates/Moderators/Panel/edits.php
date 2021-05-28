<h2>Pending Suggetions</h2>

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
		<td><?php echo h($word['created'].' ('.$this->Time->format($word['created']).')');?></td>
		<td><?php echo $this->Html->link('View Entry', '/words/edit/'.$word['id']);?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>

<h2>New Edits</h2>

<?php if (empty($newEdits)):?>

<p>There are no new submissions.</p>

<?php else: ?>

<table>
	<tr>
		<th>Word</th>
		<th>Submitted By</th>
		<th>Submitted On</th>
		<th>Status</th>
		<th></th>
	</tr>

<?php foreach ($newEdits as $word): $word = $word['Edit']; ?>
	<tr>
		<td><?php echo h($word['spelling']);?></td>
		<td><?php echo h($word['contributor_name']);?> (<?php echo h($word['contributor_email']);?>)</td>
		<td><?php echo h($word['created'].' ('.$this->NiceTime->nice($word['created']).')');?></td>
		<td><?php if ($word['status'] == "Pending") echo "Pending Approval"; else echo h($word['status']);?></td>
		<td><a href="<?php echo $this->Html->url('/moderators/edit/'.$word['id'], true);?>">View Entry</a></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>