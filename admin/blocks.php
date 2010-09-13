<?php

require_once('./../config.php');
require_once(PATH . CLASSES . 'alkaline.php');

$alkaline = new Alkaline;
$user = new User;

$user->perm(true);

$blocks = $alkaline->getBlocks();
$block_count = count($blocks);

define('TAB', 'settings');
define('TITLE', 'Alkaline Theme Blocks');
require_once(PATH . ADMIN . 'includes/header.php');

?>

<h1>Theme Blocks (<?php echo $block_count; ?>)</h1>

<table>
	<tr>
		<th>Block</th>
		<th class="center">Canvas Markup</th>
	</tr>
	<?php
	
	foreach($blocks as $block){
		echo '<tr>';
		echo '<td><strong>' . $block . '</strong></td>';
		echo '<td class="center">&#0060;&#0033;&#0045;&#0045; CANVAS_' . strtoupper(preg_replace('#\..+#si', '', $block)) . ' &#0045;&#0045;&#0062;</td>';
		echo '</tr>';
	}

	?>
</table>
	
<?php

require_once(PATH . ADMIN . 'includes/footer.php');

?>