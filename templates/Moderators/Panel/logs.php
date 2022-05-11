<?php function link_returner($wordid){
	if (' ' !== $wordid) {
        return '<a href="/words/' . trim($wordid)  . '">View Word</a>';
    } else {
        return '';
    }

}
?>

<h2>Logs</h2>

<?php if (empty($wordLogs)):?>

<p>Logs are empty.</p>

<?php else: ?>
    <h3>Word Events</h3>
<table>
	<tr>
		<th>Timestamp</th>
		<th>Event Description</th>
		<th>View Word (current state, if applicable)</th>
	</tr>

<?php foreach ($wordLogs as $wLog): //$word = $word['Edit']; ?>

	<tr>
		<td><?php echo $this->Time->format($wLog[0]);?></td>
		<td><?php echo $wLog[2]; ?></td>
		<td><?php echo link_returner($wLog[3]);?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>

<?php if (empty($pronunciationLogs)):?>

<p>Logs are empty.</p>

<?php else: ?>
    <h3>Pronunciation Events</h3>
<table>
	<tr>
		<th>Timestamp</th>
		<th>Event Description</th>
		<th>View Word (current state, if applicable)</th>
	</tr>

<?php foreach ($pronunciationLogs as $pLog): //$word = $word['Edit']; ?>

	<tr>
		<td><?php echo $this->Time->format($pLog[0]);?></td>
		<td><?php echo $pLog[2]; ?></td>
		<td><?php echo link_returner($pLog[3]);?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>

<?php if (empty($sentenceRecordingLogs)):?>

<p>Logs are empty.</p>

<?php else: ?>
    <h3>Pronunciation Events</h3>
<table>
	<tr>
		<th>Timestamp</th>
		<th>Event Description</th>
		<th>View Word (current state, if applicable)</th>
	</tr>

<?php foreach ($sentenceRecordingLogs as $pLog): //$word = $word['Edit']; ?>

	<tr>
		<td><?php echo $this->Time->format($pLog[0]);?></td>
		<td><?php echo $pLog[2]; ?></td>
		<td><?php echo ($pLog[3] ? '<a href="/SentenceRecordings/manage/' . trim($pLog[3]) . '/' . trim($pLog[4]) . '">Manage Recordings</a>' : "No word ID provided") ;?></td>
	</tr>
<?php endforeach; ?>

</table>

<?php endif;?>